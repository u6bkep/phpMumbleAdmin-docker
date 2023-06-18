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

$pid = $PMA->userProfile['id'];

/*
* Common string for logs.
*/
define('C_LOGS', ' ( profile id: #'.$pid.' - server id: #%d )');

/*
* CMD:
* Add a vserver.
*/
if (isset($cmd->PARAMS['add_vserver'])) {

    if (! $PMA->user->isMinimumAdminFullAccess()) {
        throw new PMA_cmdException('illegal_operation');
    }

    $prx = $PMA->murmurMeta->newServer();

    $cmd->logUserAction('Virtual server created'.sprintf(C_LOGS, $prx->id()));
    $PMA->message('vserver_created_success');
    $prx->setConf('boot', 'false');

    if (isset($cmd->PARAMS['new_su_pw'])) {
        $prx->setSuperuserPassword($pw = genRandomChars(16));
        $PMA->messageError(array('new_su_pw', $pw));
    }

    /*
    * Refresh vservers cache.
    */
    $cache = PMA_serversCacheHelper::get('normal', true);
    $cache = PMA_serversCacheHelper::get('htEnc', true);

/*
* CMD:
* Toggle vserver status (start/stop).
*/
} elseif (isset($cmd->PARAMS['toggle_server_status'])) {

    $sid = $cmd->PARAMS['toggle_server_status'];

    if (! ctype_digit($sid) && ! is_int($sid)) {
        throw new PMA_cmdException('illegal_operation');
    }

    /*
    * Check if SuperUsers have the authorization to start/stop the vserver.
    */
    if ($PMA->user->is(PMA_USERS_SUPERUSERS) && ! $PMA->config->get('SU_start_vserver')) {
        throw new PMA_cmdException('illegal_operation');
    }

    /*
    * Set $sid
    */
    if ($PMA->user->isMinimum(PMA_USER_ADMIN)) {
        $sid = (int)$sid;
    } else {
        $sid = $PMA->user->mumbleSID;
    }

    /*
    * Check current admin rights for the server id.
    */
    if ($PMA->user->is(PMA_USER_ADMIN) && ! $PMA->user->checkServerAccess($sid)) {
        throw new PMA_cmdException('illegal_operation');
    }

    /*
    * Get vserver proxy.
    */
    if (is_null($prx = $PMA->murmurMeta->getServer($sid))) {
        throw new PMA_cmdException('Murmur_InvalidServerException');
    }

    /*
    * Stop vserver after a confirmation.
    */
    if (isset($cmd->PARAMS['confirm_stop_sid'])) {

        if (! isset($cmd->PARAMS['confirmed'])) {
            throw new PMA_cmdExitException();
        }

        if (! $prx->isRunning()) {
            throw new PMA_cmdException('Murmur_ServerBootedException');
        }

        $message = $cmd->PARAMS['msg'];

        if ($message !== '') {
            if (! $PMA->user->isMinimumAdminFullAccess()) {
                $message = $prx->removeHtmlTags($message, $stripped);
                if ($stripped) {
                    $PMA->messageError('vserver_dont_allow_HTML');
                }
            }
            $message = $prx->URLtoHTML($message);
            $prx->sendMessageChannel(0, true, $message);
        }

        if (isset($cmd->PARAMS['kickAllUsers'])) {
            $prx->kickAllUsers();
        }

        $prx->stop();
        $cmd->logUserAction('Server stopped'.sprintf(C_LOGS, $sid));
        $prx->setConf('boot', 'false');

    /*
    * Toggle vserver status.
    */
    } else {

        if ($prx->isRunning()) {
            /*
            * Check if the vserver is empty or ask for a confirmation.
            */
            $usersList = $prx->getUsers();

            if (empty($usersList)) {
                $prx->stop();
                $cmd->logUserAction('Server stopped'.sprintf(C_LOGS, $sid));
                $prx->setConf('boot', 'false');
            } else {
                // Redirect to the confirmation message.
                $cmd->setRedirection('?confirmStopSrv='.$sid);
                throw new PMA_cmdExitException();
            }
        } else {
            $prx->start();
            $cmd->logUserAction('Server started'.sprintf(C_LOGS, $sid));
            $prx->setConf('boot', '');
        }
    }

/*
* CMD:
* Toggle web access.
*/
} elseif (isset($cmd->PARAMS['toggle_web_access'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $sid = $cmd->PARAMS['toggle_web_access'];

    if (! ctype_digit($sid)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $sid = (int)$sid;

    /*
    * Check current admin rights for the vserver.
    */
    if ($PMA->user->is(PMA_USER_ADMIN) && ! $PMA->user->checkServerAccess($sid)) {
        throw new PMA_cmdException('illegal_operation');
    }

    /*
    * Get vserver proxy.
    */
    if (is_null($prx = $PMA->murmurMeta->getServer($sid))) {
        throw new PMA_cmdException('Murmur_InvalidServerException');
    }

    if ($prx->getParameter('PMA_permitConnection') !== 'true') {
        $prx->setConf('PMA_permitConnection', 'true');
        $cmd->logUserAction('Web access enabled'.sprintf(C_LOGS, $sid));
    } else {
        // Delete the parameter
        $prx->setConf('PMA_permitConnection', '');
        $cmd->logUserAction('Web access disabled'.sprintf(C_LOGS, $sid));
    }

    /*
    * Refresh vservers cache.
    */
    $cache = PMA_serversCacheHelper::get('normal', true);
    $cache = PMA_serversCacheHelper::get('htEnc', true);

/*
* CMD:
* Delete server ID.
*/
} elseif (isset($cmd->PARAMS['delete_vserver_id'])) {

    if (! $PMA->user->isMinimumAdminFullAccess()) {
        throw new PMA_cmdException('illegal_operation');
    }

    if (! isset($cmd->PARAMS['confirmed'])) {
        throw new PMA_cmdExitException();
    }

    $sid = $cmd->PARAMS['delete_vserver_id'];

    if (! ctype_digit($sid)) {
        throw new PMA_cmdException('invalid_numerical');
    }

    $sid = (int)$sid;

    /*
    * Get vserver proxy.
    */
    if (is_null($prx = $PMA->murmurMeta->getServer($sid))) {
        throw new PMA_cmdException('Murmur_InvalidServerException');
    }

    /*
    * You can't delete a running vserver, so stop it first.
    */
    if ($prx->isRunning()) {
        $prx->kickAllUsers();
        $prx->stop();
    }

    $prx->delete();

    /*
    * Remove selected vserver if we deleted it.
    */
    if (
        isset($_SESSION['page_vserver']['id']) &&
        $_SESSION['page_vserver']['id'] === $sid
    ) {
        unset($_SESSION['page_vserver']);
    }

    $cmd->logUserAction('Virtual server deleted'.sprintf(C_LOGS, $sid));
    $PMA->message('vserver_deleted_success');

    $PMA->admins = new PMA_datas_admins();
    $PMA->admins->deleteServerIdsAccess($pid, $sid);

    /*
    * Refresh vservers cache.
    */
    $cache = PMA_serversCacheHelper::get('normal', true);
    $cache = PMA_serversCacheHelper::get('htEnc', true);

/*
* CMD:
* Send message to all servers.
*/
} elseif (isset($cmd->PARAMS['messageToServers'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $message = $cmd->PARAMS['messageToServers'];

    if ($message === '') {
        throw new PMA_cmdException('empty_message_not_allowed');
    }

    $bootedList = $PMA->murmurMeta->getBootedServers();

    foreach ($bootedList as $prx) {
        // Check admin access.
        if ($PMA->user->is(PMA_USER_ADMIN)) {
            if (! $PMA->user->checkServerAccess($prx->getSid())) {
                continue;
            }
        }
        // Strip HTML messages.
        if ($PMA->user->is(PMA_USERS_ADMINS)) {
            $message = $prx->removeHtmlTags($message, $stripped);
        }

        $message = $prx->URLtoHTML($message);
        $prx->sendMessageChannel(0, true, $message);
    }

/*
* CMD:
* Refresh servers list.
*/
} elseif (isset($cmd->PARAMS['refreshServerList'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $cmd->setRedirection('referer');
    $cache = PMA_serversCacheHelper::get('normal', true);
    $cache = PMA_serversCacheHelper::get('htEnc', true);

/*
* CMD:
* Reset all parameters of a vserver.
*/
} elseif (isset($cmd->PARAMS['serverReset'])) {

    if (! isset($cmd->PARAMS['confirmed'])) {
        throw new PMA_cmdExitException();
    }

    $sid = $cmd->PARAMS['serverReset'];

    if (! $PMA->user->isMinimum(PMA_USER_ADMIN) OR ! ctype_digit($sid)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $sid = (int)$sid;

    // Check current admin rights for the vserver
    if ($PMA->user->is(PMA_USER_ADMIN) && ! $PMA->user->checkServerAccess($sid)) {
        throw new PMA_cmdException('illegal_operation');
    }

    /*
    * Get vserver proxy.
    */
    if (is_null($prx = $PMA->murmurMeta->getServer($sid))) {
        throw new PMA_cmdException('Murmur_InvalidServerException');
    }

    /*
    * Lot of actions require a started vserver.
    */
    if (! $prx->isRunning()) {
        $prx->start();
    }

    /*
    * Action:
    * First, kick all users.
    */
    $prx->kickAllUsers();

    /*
    * Action:
    * Delete all channels.
    */
    $channels = $prx->getChannels();
    foreach ($channels as $chan) {
        if ($chan->id !== 0 && $chan->parent === 0) {
            $prx->removeChannel($chan->id);
        }
    }

    /*
    * Action:
    * Reset root channel properties.
    */
    $root = $prx->getChannelState(0);
    $root->name = 'Root';
    $root->links = array();
    $root->description = '';
    $root->position = 0;
    $prx->setChannelState($root);

    /*
    * Action:
    * Reset root channel ACLs.
    */
    $aclList = array();

    $aclList[1] = PMA_MurmurObjectFactory::getAcl();
    $aclList[1]->group = 'admin';
    $aclList[1]->userid = -1;
    $aclList[1]->applyHere = true;
    $aclList[1]->applySubs = true;
    $aclList[1]->inherited = false;
    $aclList[1]->allow = Murmur_PermissionWrite;
    $aclList[1]->deny = 0;

    $aclList[2] = PMA_MurmurObjectFactory::getAcl();
    $aclList[2]->group = 'auth';
    $aclList[2]->userid = -1;
    $aclList[2]->applyHere = true;
    $aclList[2]->applySubs = true;
    $aclList[2]->inherited = false;
    $aclList[2]->allow = Murmur_PermissionMakeTempChannel;
    $aclList[2]->deny = 0;

    $aclList[3] = PMA_MurmurObjectFactory::getAcl();
    $aclList[3]->group = 'all';
    $aclList[3]->userid = -1;
    $aclList[3]->applyHere = true;
    $aclList[3]->applySubs = false;
    $aclList[3]->inherited = false;
    $aclList[3]->allow = Murmur_PermissionRegisterSelf;
    $aclList[3]->deny = 0;

    /*
    * Action:
    * Reset root channel groups.
    */
    $groupList = array();

    $groupList[1] = PMA_MurmurObjectFactory::getGroup();
    $groupList[1]->name = 'admin';
    $groupList[1]->inherited = false;
    $groupList[1]->inherit = true;
    $groupList[1]->inheritable = true;
    $groupList[1]->add = array();
    $groupList[1]->members = array();
    $groupList[1]->remove = array();

    $prx->setACL(0, $aclList, $groupList, false);

    /*
    * Action:
    * Reset vserver parameters.
    */
    $confList = $prx->getAllConf();
    foreach ($confList as $key => $value) {
        $prx->setConf($key, '');
    }

    /*
    * Action:
    * Delete all registered accounts.
    */
    $registrationsList = $prx->getRegisteredUsers('');
    foreach ($registrationsList as $uid => $name) {
        if ($uid !== 0) {
            $prx->unregisterUser($uid);
        }
    }

    /*
    * Action:
    * Reset SuperUser registration.
    */
    $resetSu[0] = 'SuperUser';
    $resetSu[1] = '';
    $resetSu[2] = '';
    $prx->updateRegistration(0, $resetSu);

    /*
    * Action:
    * New SuperUser password.
    */
    if (isset($cmd->PARAMS['new_su_pw'])) {
        $prx->setSuperuserPassword($pw = genRandomChars(16));
        $PMA->messageError(array('new_su_pw', $pw));
    }

    /*
    * Action:
    * Delete all bans.
    */
    $prx->setBans(array());

    /*
    * Action:
    * End.
    */
    $prx->stop();
    $prx->setConf('boot', 'false');

    if (
        isset($_SESSION['page_vserver']['id']) &&
        $_SESSION['page_vserver']['id'] === $sid
    ) {
        unset($_SESSION['page_vserver']);
    }

    $cmd->logUserAction('Virtual server reseted'.sprintf(C_LOGS, $sid));
    $PMA->message('vserver_reset_success');

/*
* CMD:
* Set mass settings.
*/
} elseif (isset($cmd->PARAMS['mass_settings'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $cmd->setRedirection('referer');

    if ($cmd->PARAMS['confirm'] !== $cmd->PARAMS['confirm_word']) {
        throw new PMA_cmdException('invalid_confirm_word');
    }

    $settings = PMA_MurmurSettingsHelper::get($PMA->murmurMeta->getVersionInt());

    // Check for a valid parameter
    if (! isset($settings[$cmd->PARAMS['key']])) {
        throw new PMA_cmdException('invalid_setting_parameter');
    }

    $vserversList = $PMA->murmurMeta->getAllServers();

    foreach ($vserversList as $prx) {
        $prx->setConf($cmd->PARAMS['key'], $cmd->PARAMS['value']);
    }

    $PMA->message('parameters_updated_success');
}
