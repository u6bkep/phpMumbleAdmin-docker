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

$cid = $_SESSION['page_vserver']['cid'];

/*
* CMD:
* Add a sub channel.
*/
if (isset($cmd->PARAMS['add_sub_channel'])) {

    $name = $cmd->PARAMS['add_sub_channel'];

    $CHAN = $prx->getChannelState($cid);

    /*
    * Don't add a sub channel if parent is a temporary.
    * Mumble doesn't allow it, so there is no reason to do it.
    */
    if ($CHAN->temporary) {
        throw new PMA_cmdException('temporary_channel');
    }

    if (! $prx->validateChannelChars($name)) {
        throw new PMA_cmdException('invalid_channel_name');
    }

    $new = $prx->addChannel($name, $cid);

    $_SESSION['page_vserver']['cid'] = $new;
    $PMA->router->subtab->setCurrentRoute('properties');

    unset(
        $_SESSION['page_vserver']['aclID'],
        $_SESSION['page_vserver']['groupID']
    );

/*
* CMD:
* Send a message to a channel.
*/
} elseif (isset($cmd->PARAMS['send_msg'])) {

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
    $toSubChannels = isset($cmd->PARAMS['to_all_sub']);
    $prx->sendMessageChannel($cid, $toSubChannels, $message);

/*
* CMD:
* Delete a channel.
*/
} elseif (isset($cmd->PARAMS['delete_channel'])) {

    if (! isset($cmd->PARAMS['confirmed'])) {
        throw new PMA_cmdExitException();
    }

    $CHAN = $prx->getChannelState($cid);

    $prx->removeChannel($cid);

    $_SESSION['page_vserver']['cid'] = $CHAN->parent;

    /*
    * Remove defaultChannel if we have deleted the default channel.
    */
    if ($prx->getParameter('defaultchannel') === (string) $cid) {
        $prx->setConf('defaultchannel' , '');
    }

    unset(
        $_SESSION['page_vserver']['aclID'],
        $_SESSION['page_vserver']['groupID']
    );

/*
* CMD:
* Edit channel property.
*/
} elseif (isset($cmd->PARAMS['channel_property'])) {

    $state = $prx->getChannelState($cid);

    /*
    * Set default channel.
    */
    if (isset($cmd->PARAMS['defaultchannel']) && ! $state->temporary) {
        // Memo: setConf() require a string for second parameter
        $prx->setConf('defaultchannel', (string) $cid);
    }

    /*
    * Set channel name.
    */
    if (isset($cmd->PARAMS['name']) && $state->name !== $cmd->PARAMS['name']) {
        if ($prx->validateChannelChars($cmd->PARAMS['name'])) {
            $state->name = $cmd->PARAMS['name'];
        } else {
            $PMA->messageError('invalid_channel_name');
        }
    }

    /*
    * Set channel description.
    */
    if ($state->description !== $cmd->PARAMS['description']) {
        // As anybody can modify channel description, always remove HTML tags
        $state->description = $prx->removeHtmlTags($cmd->PARAMS['description'], $stripped);
        if ($stripped) {
            $PMA->messageError('vserver_dont_allow_HTML');
        }
    }

    /*
    * Set channel position.
    */
    if (is_numeric($cmd->PARAMS['position']) OR $cmd->PARAMS['position'] === '') {
        $state->position = (int)$cmd->PARAMS['position'];
    } else {
        $PMA->messageError(array('invalid_numerical', 'channel position'));
    }

    $prx->setChannelState($state);

    /*
    * Set channel password.
    */
    $prx->getACL($cid, $aclList, $groupList, $inherit);

    PMA_MurmurAclHelper::rmInheritedAcl($aclList);
    PMA_MurmurAclHelper::rmInheritedGroups($groupList);

    // Check if a password is set.
    $password_is_set = false;
    $password_acl_id = '';

    foreach ($aclList as $key => $acl) {
        if (PMA_MurmurAclHelper::isToken($acl)) {
            $password_is_set = true;
            $password_acl_id = $key;
            break;
        }
    }

    if ($cmd->PARAMS['pw'] !== '') {
        // Add a new password
        if (! $password_is_set) {
            // Deny all ACL
            $deny_all = PMA_MurmurObjectFactory::getAcl();
            $deny_all->group = 'all';
            $deny_all->userid = -1;
            $deny_all->inherited = false;
            $deny_all->applyHere = true;
            $deny_all->applySubs = true;
            $deny_all->allow = 0;
            $deny_all->deny = 908;

            // Password ACL
            $password = PMA_MurmurObjectFactory::getAcl();
            $password->group = '#'.$cmd->PARAMS['pw'];
            $password->userid = -1;
            $password->inherited = false;
            $password->applyHere = true;
            $password->applySubs = true;
            $password->allow = 908;
            $password->deny = 0;

            $aclList[] = $deny_all;
            $aclList[] = $password;

        // edit password
        } else {
            $aclList[$password_acl_id]->group = '#'.$cmd->PARAMS['pw'];
        }

    // Delete the password if the field is empty and a password was set.
    } elseif ($password_is_set) {

        unset($aclList[$password_acl_id]);

        // Search for the "deny all" ACL included with the password creation.
        foreach ($aclList as $key => $acl) {

            if (
                $acl->group === 'all'
                && ! $acl->inherited
                && $acl->applyHere
                && $acl->applySubs
                && $acl->allow === 0
                && $acl->deny === 908
            ) {
                $deny_all_acl_id = $key;
                unset($aclList[$key]);
                break;
            }
        }

        if (isset($_SESSION['page_vserver']['aclID'])) {
            // Unset selected acl if it's the password or "deny all".
            if ($_SESSION['page_vserver']['aclID'] === $password_acl_id
            OR $_SESSION['page_vserver']['aclID'] === $deny_all_acl_id
            ) {
                unset($_SESSION['page_vserver']['aclID']);
            }
        }
    }

    $prx->setACL($cid, $aclList, $groupList, $inherit);

/*
* CMD:
* Move users out the channel.
*/
} elseif (isset($cmd->PARAMS['move_users_out_the_channel'])) {

    $move_to_cid = $cmd->PARAMS['move_users_out_the_channel'];

    if (! ctype_digit($move_to_cid)) {
        throw new PMA_cmdException('invalid_numerical');
    }

    $move_to_cid = (int)$move_to_cid;

    $users = $prx->getUsers();

    /*
    * Move users currently in the selected channel,
    * and choosen by the admin.
    */
    foreach ($users as $user) {
        if (
            $user->channel === $cid &&
            isset($cmd->PARAMS[$user->session])
        ) {
            $user->channel = $move_to_cid;
            $prx->setState($user);
        }
    }

/*
* CMD:
* Move users into the channel.
*/
} elseif (isset($cmd->PARAMS['move_users_into_the_channel'])) {

    $users = $prx->getUsers();

    /*
    * Move users currently out of the selected channel,
    * and choosen by the admin.
    */
    foreach ($users as $user) {
        if (
            $user->channel !== $cid &&
            isset($cmd->PARAMS[$user->session])
        ) {
            $user->channel = $cid;
            $prx->setState($user);
        }
    }

/*
* CMD:
* Move a channel.
*/
} elseif (isset($cmd->PARAMS['move_channel_to'])) {

    $id = $cmd->PARAMS['move_channel_to'];

    if (! ctype_digit($id)) {
        throw new PMA_cmdException('invalid_numerical');
    }

    $id = (int)$id;

    $state = $prx->getChannelState($cid);

    if ($state->parent === $id) {
        throw new PMA_cmdException('parent_channel');
    }

    $state->parent = $id;

    try {
        $prx->setChannelState($state);
    } catch (Murmur_InvalidChannelException $Ex) {
        // Most probably we moved to a children channel.
        throw new PMA_cmdException('children_channel');
    }

/*
* CMD:
* Link a channel.
*/
} elseif (isset($cmd->PARAMS['link_channel'])) {

    $cmd->setRedirection('referer');

    $id = $cmd->PARAMS['link_channel'];

    if (! ctype_digit($id)) {
        throw new PMA_cmdException('invalid_numerical');
    }

    $id = (int)$id;

    $channelsList = $prx->getChannels();
    $chanObj = $prx->getChannelState($cid);

    if ($chanObj->id !== $id) {
        $chanObj->links[] = $id;
        $prx->setChannelState($chanObj);
    }

    $a = count($channelsList);
    $b = (count($chanObj->links) +1);

    /*
    * If all channels are linked with the selected channel, no need to
    * redirect to the selected channel.
    */
    if ($a === $b) {
        $cmd->setRedirection(null);
    }

/*
* CMD:
* Unlink a channel.
*/
} elseif (isset($cmd->PARAMS['unlink_channel'])) {

    $cmd->setRedirection('referer');

    $id = $cmd->PARAMS['unlink_channel'];

    if (! ctype_digit($id)) {
        throw new PMA_cmdException('invalid_numerical');
    }

    $id = (int)$id;

    $chanObj = $prx->getChannelState($cid);

    foreach ($chanObj->links as $key => $chanID) {
        if ($chanID === $id) {
            unset($chanObj->links[$key]);
            $prx->setChannelState($chanObj);
            break;
        }
    }

    if (empty($chanObj->links)) {
        $cmd->setRedirection(null);
    }

/*
* CMD:
* Unlink all channels.
*/
} elseif (isset($cmd->PARAMS['unlink_all_channel'])) {
    $channel = $prx->getChannelState($cid);
    $channel->links = array();
    $prx->setChannelState($channel);
}


