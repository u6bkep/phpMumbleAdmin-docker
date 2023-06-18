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
* User Class Sanity.
*
* Check for user class,
* require PMA_USER_UNAUTH only.
*/
if (! $PMA->user->is(PMA_USER_UNAUTH)) {
    throw new PMA_cmdException('already_authenticated');
}

/*
* Autoban attempts.
*/
require 'common.autoban.php';

/*
* Sanity.
* Empty login is always an error.
*/
if (! isset($cmd->PARAMS['login']) OR $cmd->PARAMS['login'] === '') {
    $PMA->log('auth.error', 'empty login');
    throw new PMA_cmdException('auth_error');
}

/*
* Sanity.
* Empty password is always an error too.
*/
if (! isset($cmd->PARAMS['password']) OR $cmd->PARAMS['password'] === '') {
    $PMA->log('auth.error', 'empty password');
    throw new PMA_cmdException('auth_error');
}

/*
* Sanity.
* Server id.
*/
if (! isset($cmd->PARAMS['server_id'])) {
    $cmd->PARAMS['server_id'] = '';
}
if ($cmd->PARAMS['server_id'] !== '' && ! ctype_digit($cmd->PARAMS['server_id'])) {
    $PMA->log('auth.error', 'invalid server id "'.$cmd->PARAMS['server_id'].'"');
    throw new PMA_cmdException('auth_error');
}

/*
* Setup auth variables.
*/
$cmd->login = $cmd->PARAMS['login'];
$cmd->password = $cmd->PARAMS['password'];
$cmd->sid = $cmd->PARAMS['server_id'];
$cmd->ip = $_SERVER['REMOTE_ADDR'];

/*
* CMD:
* Authenticate PMA users.
*/
if ($cmd->sid === '') {
    /*
    * Authenticate SuperAdmin.
    */
    if ($cmd->login === $PMA->config->get('SA_login')) {
        if (PMA_passwordHelper::check($cmd->password, $PMA->config->get('SA_pw'))) {
            $PMA->user->setClass(PMA_USER_SUPERADMIN);
            $PMA->user->setLogin($PMA->config->get('SA_login'));
            $PMA->user->setAuthIP($cmd->ip);
            $PMA->cookie->requestUpdate();
            $PMA->log('auth.info', 'Successful login for SuperAdmin');
        } else {
            $PMA->log('auth.error', 'Password error for SuperAdmin');
            throw new PMA_cmdException('auth_error');
        }
    /*
    * Authenticate admins.
    */
    } else {
        $PMA->admins = new PMA_datas_admins();
        $adm = $PMA->admins->auth($cmd->login, $cmd->password);
        if (is_array($adm)) {
            $PMA->user->setClass($adm['class']);
            $PMA->user->setLogin($adm['login']);
            $PMA->user->setAdminID($adm['id']);
            $PMA->user->setAuthIP($cmd->ip);
            // Update last connection timestamp.
            $adm['last_conn'] = time();
            $PMA->admins->modify($adm);
            $PMA->cookie->requestUpdate();
            $PMA->log('auth.info', 'Successful login for '.pmaGetClassName($adm['class']).' "'.$cmd->login.'"');
        } elseif ($adm === 1) {
            $PMA->log('auth.error',  'Password error for admin "'.$cmd->login.'"');
            throw new PMA_cmdException('auth_error');
        } else {
            $PMA->log('auth.error', 'Login error: no admin "'.$cmd->login.'" found.');
            throw new PMA_cmdException('auth_error');
        }
    }
/*
* CMD:
* Authenticate mumble users.
*/
} elseif (ctype_digit($cmd->sid)) {

        $allowOfflineAuth = $PMA->config->get('allowOfflineAuth');
        $allowSuperUserAuth = $PMA->config->get('SU_auth');
        $allowMumbleUsersAuth = $PMA->config->get('RU_auth');
        $allowSuperUserRuClass = $PMA->config->get('SU_ru_active');
        $isSuperUserRu = false;

        if (! $allowSuperUserAuth && ! $allowMumbleUsersAuth) {
            throw new PMA_cmdException('auth_error');
        }

        require PMA_DIR_INCLUDES.'iceConnection.inc';
        if (! $PMA->murmurMeta->isConnected()) {
            throw new PMA_cmdException();
        }

        $sid = (int)$cmd->sid;
        $profile = $PMA->userProfile;

        // Common pmaLogs infos
        $logsInfos = ' ( profile: '.$profile['id'].'# server id: '.$sid.' -  login: '.$cmd->login.' )';

        if (is_null($prx = $PMA->murmurMeta->getServer($sid))) {
            $PMA->log('auth.error', 'Server id do not exists'.$logsInfos);
            throw new PMA_cmdException('auth_error');
        }
        // Check web access
        if ($prx->getParameter('PMA_permitConnection') !== 'true') {
            $PMA->log('auth.warn', 'Web access denied'.$logsInfos);
            throw new PMA_cmdException('web_access_disabled');
        }
        $isRunning = $prx->isRunning();
        // Start the server if stopped.
        if (! $isRunning) {
            if ($allowOfflineAuth) {
                $prx->start();
            } else {
                throw new PMA_cmdException('vserver_offline');
            }
        }
        // verifyPassword return user ID on successfull authentification, else
        // -1 for failed authentication and -2 for unknown usernames.
        $MumbleID = $prx->verifyPassword($cmd->login, $cmd->password);
        // Get registration before stop the vserver
        if ($MumbleID >= 0) {
            $user_registration = $prx->getRegistration($MumbleID);
        }
        // Check if registered user have SuperUserRu rights
        if ($allowSuperUserAuth && $allowSuperUserRuClass) {
            $prx->getACL(0, $aclList, $groupList, $inherit);
            $isSuperUserRu = PMA_MurmurAclHelper::isSuperUserRu($MumbleID, $aclList);
        }
        // Stop the server if it was stopped.
        if (! $isRunning) {
            $prx->stop();
        }
        // PASSWORD ERROR
        if ($MumbleID === -1) {
            $PMA->log('auth.error', 'Password error:'.$logsInfos);
            throw new PMA_cmdException('auth_error');
        }
        // INVALID LOGIN
        if ($MumbleID === -2) {
            $PMA->log('auth.error', 'Login error:'.$logsInfos);
            throw new PMA_cmdException('auth_error');
        }
        // Check if SuperUser connection is authorized.
        if ($MumbleID === 0 && ! $allowSuperUserAuth) {
            $PMA->log('auth.warn', 'SuperUsers authentication not allowed'.$logsInfos);
            throw new PMA_cmdException('auth_su_disabled');
        }
        // Check if registered user connection is authorized, but let connect SuperUser_ru anyway.
        if ($MumbleID > 0 && ! $allowMumbleUsersAuth && ! $isSuperUserRu) {
            $PMA->log('auth.warn', 'Registered users authentication not allowed'.$logsInfos);
            throw new PMA_cmdException('auth_ru_disabled');
        }
        // Succesfull login, setup the session
        if ($MumbleID === 0) {
            $PMA->user->setClass(PMA_USER_SUPERUSER);
        } elseif ($isSuperUserRu) {
            $PMA->user->setClass(PMA_USER_SUPERUSER_RU);
        } else {
            $PMA->user->setClass(PMA_USER_MUMBLE);
        }
        $PMA->user->setLogin($user_registration[0]);
        $PMA->user->setAuthProfileID($profile['id']);
        $PMA->user->setAuthProfileHost($profile['host']);
        $PMA->user->setAuthProfilePort($profile['port']);
        $PMA->user->setMumbleSID($sid);
        $PMA->user->setMumbleUID($MumbleID);
        $PMA->user->setAuthIP($cmd->ip);

        $PMA->log('auth.info', 'Successful login for '.pmaGetClassName($PMA->user->class).$logsInfos);
        $PMA->cookie->requestUpdate();
} else {
    throw new PMA_cmdException('auth_error');
}






