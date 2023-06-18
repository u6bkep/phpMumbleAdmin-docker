<?php

 /*
 * phpMumbleAdmin (PMA), administration panel for murmur (mumble server daemon).
 * Copyright (C) 2010 - 2016, Dadon David, dev.pma@ipnoz.net
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if (! defined('PMA_STARTED')) { die('You cannot call this script directly !'); }

/*
* Autoban attempts.
*/
require 'common.autoban.php';

/*
* Check for sanity:
* Password generation must be enable in the options.
* User must accept cookies.
* SuperUser OR Registered user web access must be enabled.
*/
if (
    ! $PMA->config->get('pw_gen_active') OR
    ! $PMA->cookie->userAcceptCookies() OR
    (! $PMA->config->get('SU_auth') && ! $PMA->config->get('RU_auth'))
) {
    throw new PMA_cmdException('illegal_operation');
}

/*
* Initiate Murmur connection.
*/
require PMA_DIR_INCLUDES.'iceConnection.inc';
if (! $PMA->murmurMeta->isConnected()) {
    throw new PMA_cmdException();
}

$sid = (int)$cmd->PARAMS['server_id'];
$login = $cmd->PARAMS['login'];

$showExplicitErrors = $PMA->config->get('pw_gen_explicit_msg');

/*
* Common string for logs.
*/
define('C_LOGS',
    sprintf(' ( profile id: #%d - server id: #%d - login: %s )',
        $PMA->userProfile['id'],
        $sid,
        $login
    )
);

/*
* Get vserver proxy.
*/
if (is_null($prx = $PMA->murmurMeta->getServer($sid))) {
    $PMA->log('pwGen.error', 'Invalid server id'.C_LOGS);
    if ($showExplicitErrors) {
        throw new PMA_cmdException('gen_pw_invalid_server_id');
    } else {
        throw new PMA_cmdException('gen_pw_error');
    }
}

/*
* Check if web access is enable.
*/
if ($prx->getParameter('PMA_permitConnection') !== 'true') {
    $PMA->log('pwGen.warn', 'Web access is disabled'.C_LOGS);
    throw new PMA_cmdException('web_access_disabled');
}

/*
* Start the virtual server if it's stopped.
* WARNING:
* At this point, do not exit the process before stopping the vserver.
*/
if (! $prx->isRunning()) {
    $prx->start();
    $serverIsTemporaryStarted = true;
} else {
    $serverIsTemporaryStarted = false;
}

/*
* Fetch user ID.
*
* Memo:
* getRegisteredUsers() return all occurence of a search.
* example: for "ipnoz", it's return ipnozer, ipnozer2 etc...
*/
$searchList = $prx->getRegisteredUsers($login);

$name = strtolower($login);
unset($mumbleID);

foreach ($searchList as $regid => $regName) {
    if (strtolower($regName) === $name) {
        // User login exists, keep user ID.
        $mumbleID = $regid;
        break;
    }
}

// user not found
if (! isset($mumbleID)) {
    $PMA->log('pwGen.error', 'User not found'.C_LOGS);
    if ($serverIsTemporaryStarted) {
        $prx->stop();
    }
    if ($showExplicitErrors) {
        throw new PMA_cmdException('gen_pw_invalid_username');
    } else {
        throw new PMA_cmdException('gen_pw_error');
    }
// SuperUser
} elseif ($mumbleID === 0) {
    $PMA->log('pwGen.error', 'SuperUser is denied'.C_LOGS);
    if ($serverIsTemporaryStarted) {
        $prx->stop();
    }
    if ($showExplicitErrors) {
        throw new PMA_cmdException('gen_pw_su_denied');
    } else {
        throw new PMA_cmdException('gen_pw_error');
    }
// User found and valid
} elseif ($mumbleID > 0) {
    $uid = $mumbleID;
// Unknown error
} else {
    if ($serverIsTemporaryStarted) {
        $prx->stop();
    }
    $PMA->debugError('Unknown error');
    $PMA->log('pwGen.error', 'Unknown error'.C_LOGS);
    throw new PMA_cmdException('gen_pw_error');
}

/*
* Fetch user email.
*/
$registration = $prx->getRegistration($uid);

if (isset($registration[1])) {
    $email = $registration[1];
} else {
    $email = '';
}

if ($email === '') {
    $PMA->log('pwGen.warn', 'empty email'.C_LOGS);
    if ($serverIsTemporaryStarted) {
        $prx->stop();
    }
    if ($showExplicitErrors) {
        throw new PMA_cmdException('gen_pw_empty_email');
    } else {
        throw new PMA_cmdException('gen_pw_error');
    }
}

/*
* Remove identical requests.
*/
$PMA->pw_requests = new PMA_datas_pwRequests();
$PMA->pw_requests->deleteIdenticalRequests(
    $PMA->userProfile['id'],
    $PMA->userProfile['host'],
    $PMA->userProfile['port'],
    $sid,
    $uid
);

/*
* Contruct the request.
*/
$pending = $PMA->config->get('pw_gen_pending');

$new_request['id'] = $PMA->pw_requests->getUniqueID();
$new_request['start'] = time();
$new_request['end'] = time() + $pending * 3600;
$new_request['ip'] = $_SERVER['REMOTE_ADDR'];
$new_request['profile_id'] = $PMA->userProfile['id'];
$new_request['profile_host'] = $PMA->userProfile['host'];
$new_request['profile_port'] = $PMA->userProfile['port'];
$new_request['sid'] = $sid;
$new_request['uid'] = $uid;
$new_request['login'] = $login;

$URL = PMA_HTTP_HOST.PMA_HTTP_PATH.'?confirm_pw_request='.$new_request['id'];

/*
* Setup mail object.
*/
pmaLoadLanguage('confirmPasswordRequest');

$mail = new PMA_mail();
$mail->setHost($PMA->config->get('smtp_host'));
$mail->setPort($PMA->config->get('smtp_port'));
$mail->setDefaultSender($PMA->config->get('smtp_default_sender_email'));
$mail->setXmailer(PMA_PROJECT_NAME);
$mail->setFrom($PMA->config->get('pw_gen_sender_email'));
$mail->addTo($email, $login);
$mail->setSubject($TEXT['pw_mail_title']);
$mail->setMessage(
    sprintf($TEXT['pw_mail_body'],
        $_SERVER['HTTP_HOST'],
        $PMA->userProfile['name'],
        $prx->getParameter('registername'),
        $URL,
        $pending
    )
);

/*
* Send mail.
*/
$mail->send_mail();
foreach ($mail->smtpDialogues as $dial) {
    $PMA->debug($dial->debug);
}

/*
* End.
*/
if ($serverIsTemporaryStarted) {
    $prx->stop();
}

if ($mail->smtpError) {
    $PMA->log('smtp.error', $mail->smtpErrorMessage);
    throw new PMA_cmdException('gen_pw_error');
} else {
    $PMA->message('gen_pw_mail_sent');
    $PMA->log('pwGen.info', 'Mail sent'.C_LOGS);
    $PMA->pw_requests->add($new_request);
}


