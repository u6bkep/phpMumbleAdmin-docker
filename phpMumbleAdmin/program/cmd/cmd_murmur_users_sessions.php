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
* require PMA_USER_SUPERUSER_RU minimum.
*/
if (! $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
    throw new PMA_cmdException('illegal_operation');
}

/*
* Initiate Murmur connection.
*/
require PMA_DIR_INCLUDES.'iceConnection.inc';
if (! $PMA->murmurMeta->isConnected()) {
    throw new PMA_cmdException();
}

/*
* Get vserver proxy.
*/
if (is_null($prx = $PMA->murmurMeta->getServer($_SESSION['page_vserver']['id']))) {
    throw new PMA_cmdException('Murmur_InvalidServerException');
}

$usid = $_SESSION['page_vserver']['uSess']['id'];

/*
* CMD:
* Kick user.
*/
if (isset($cmd->PARAMS['kick'])) {

    $message = $cmd->PARAMS['kick'];

    $prx->kickUser($usid, $message);
    unset($_SESSION['page_vserver']['uSess']);

/*
* CMD:
* Move user.
*/
} elseif (isset($cmd->PARAMS['move_user_to'])) {

    $cid = $cmd->PARAMS['move_user_to'];

    if (! ctype_digit($cid)) {
        throw new PMA_cmdException('invalid_numerical');
    }

    $cid = (int)$cid;

    $user = $prx->getState($usid);

    if ($user->channel !== $cid) {
        $user->channel = $cid;
        $prx->setState($user);
    }

/*
* CMD:
* Modify session name.
*/
} elseif (isset($cmd->PARAMS['modify_user_session_name'])) {

    /*
    * Modify session name is possible since murmur 1.2.4.
    */
    if ($PMA->murmurMeta->getVersionInt() < 124) {
        throw new PMA_cmdException('murmur_124_required');
    }

    $newName = $cmd->PARAMS['modify_user_session_name'];

    $user = $prx->getState($usid);

    if ($user->name !== $newName && $newName !== '') {
        $user->name = $newName;
        $prx->setState($user);
        $_SESSION['page_vserver']['uSess']['name'] = $newName;
    }

/*
* CMD:
* Toggle mute.
*/
} elseif (isset($cmd->PARAMS['muteUser'])) {

    $user = $prx->getState($usid);

    if ($user->mute) {
        $user->mute = false;
        $user->deaf = false;
    } else {
        $user->mute = true;
    }

    $prx->setState($user);

/*
* CMD:
* Toggle deaf.
*/
} elseif (isset($cmd->PARAMS['deafUser'])) {

    $user = $prx->getState($usid);
    $user->deaf = ! $user->deaf;
    $prx->setState($user);

/*
* CMD:
* Toggle priority speaker.
*/
} elseif (isset($cmd->PARAMS['togglePrioritySpeaker'])) {

    $user = $prx->getState($usid);
    $user->prioritySpeaker = ! $user->prioritySpeaker;
    $prx->setState($user);

/*
* CMD:
* Send message.
*/
} elseif (isset($cmd->PARAMS['send_msg'])) {

    $cmd->setRedirection('referer');

    $message = $cmd->PARAMS['send_msg'];

    if ($message === '') {
        throw new PMA_cmdException('empty_message_not_allowed');
    }

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        $message = $prx->removeHtmlTags($message, $stripped);
        if ($stripped) {
            $PMA->messageError('vserver_dont_allow_HTML');
        }
    }

    $message = $prx->URLtoHTML($message);
    $prx->sendMessage($usid, $message);

/*
* CMD:
* Register user session.
*/
} elseif (isset($cmd->PARAMS['register_session'])) {

    $user = $prx->getState($usid);
    $certificatesList = $prx->getCertificateList($usid);

    if (empty($certificatesList)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $sha1 = sha1(decimalArrayToChars($certificatesList[0]));

    $newUser = array(0 => $user->name, 3 => $sha1);

    try {
        $prx->registerUser($newUser);
    } catch (Murmur_InvalidUserException $e) {
        throw new PMA_cmdException('user_already_registered');
    }

    $PMA->message('registration_created_success');

/*
* CMD:
* Modify user comment.
*/
} elseif (isset($cmd->PARAMS['change_user_comment'])) {

    $comment = $cmd->PARAMS['change_user_comment'];
    $user = $prx->getState($usid);

    if ($comment !== $user->comment) {
        $user->comment = $prx->removeHtmlTags($comment, $stripped);
        if ($stripped) {
            $PMA->messageError('vserver_dont_allow_HTML');
        }
        $prx->setState($user);
    }
}
