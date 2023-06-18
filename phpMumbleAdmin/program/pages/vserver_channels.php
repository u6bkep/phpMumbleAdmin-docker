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
* Setup action menu class.
*/
class channelsMenuStruc
{
    public $href;
    public $img = PMA_IMG_SPACE_16;
    public $text;
    public $js;
}

/*
* Load mumble images definitions.
*/
require PMA_DIR_INCLUDES.'def.images.mumble.inc';
/*
* Booted server is required.
*/
if (! $page->vserverIsBooted) {
    throw new PMA_pageException('vserver_offline_info');
}
/*
* Setup viewer widget.
*/
$PMA->widgets->newModule('viewer');
$PMA->skeleton->addCssFile('viewer.css');
/*
* Load language variables.
*/
pmaLoadLanguage('vserver_channels');
/*
* Get datas from $prx.
*/
try {
    $getTree = $prx->getTree();
} catch (Ice_MemoryLimitException $e) {
    $PMA->messageError('Ice_MemoryLimitException_Tree');
    $getTree = PMA_MurmurObjectFactory::getTree();
}
$page->channelsList = $prx->getChannels();
$page->defaultChannelID = (int)$prx->getParameter('defaultchannel');
/*
* Setup actionMenu list.
* Setup channel state ( user or channel viewBox )
* Setup the total of channels.
*/
$page->actionMenu = array();
$totalChannels = count($page->channelsList);
/*
* Add info panel : total of channels
*/
if ($page->showInfoPanel) {
    $text = sprintf($TEXT['fill_channels'], '<mark>'.$totalChannels.'</mark>');
    $PMA->infoPanel->addFillOccas($text);
}
/*
* Setup captions
*/
$PMA->captions->add(PMA_IMG_MUMBLE_HOME,          $TEXT['img_defaultchannel']);
$PMA->captions->add(PMA_IMG_MUMBLE_LINK_WITH,     $TEXT['img_linked']);
$PMA->captions->add(PMA_IMG_MUMBLE_LINK_DIRECT,   $TEXT['img_link_direct']);
$PMA->captions->add(PMA_IMG_MUMBLE_LINK_INDIRECT, $TEXT['img_link_undirect']);
$PMA->captions->add(PMA_IMG_MUMBLE_PW,            $TEXT['img_channel']);
$PMA->captions->add(PMA_IMG_MUMBLE_TEMP,          $TEXT['img_temp']);
$PMA->captions->add(PMA_IMG_MUMBLE_COMMENT,       $TEXT['img_comment']);
$PMA->captions->add(PMA_IMG_MUMBLE_AUTH,          $TEXT['img_auth']);
$PMA->captions->add(PMA_IMG_MUMBLE_REC,           $TEXT['img_recording']);
$PMA->captions->add(PMA_IMG_MUMBLE_MIC,           $TEXT['img_priorityspeaker']);
$PMA->captions->add(PMA_IMG_MUMBLE_SUPRESSED,     $TEXT['img_supressed']);
$PMA->captions->add(PMA_IMG_MUMBLE_MUTED,         $TEXT['img_muted']);
$PMA->captions->add(PMA_IMG_MUMBLE_DEAFENED,      $TEXT['img_deafened']);
$PMA->captions->add(PMA_IMG_MUMBLE_SELF_MUTE,     $TEXT['img_mute']);
$PMA->captions->add(PMA_IMG_MUMBLE_SELF_DEAF,     $TEXT['img_deaf']);
/*
* Route channels.
*/
if (isset($_GET['channel']) && ctype_digit($_GET['channel'])) {
    $_GET['channel'] = (int)$_GET['channel'];
    // Remove acl id & group id if we change channel id
    if (isset($_SESSION['page_vserver']['cid']) && $_SESSION['page_vserver']['cid'] !== $_GET['channel']) {
        unset($_SESSION['page_vserver']['aclID'], $_SESSION['page_vserver']['groupID']);
    }
    $_SESSION['page_vserver']['cid'] = $_GET['channel'];
    // Remove user session id
    unset($_SESSION['page_vserver']['uSess']);
}
/*
* Route users sessions.
*/
if (isset($_GET['userSession'])) {
    list($id, $name) = explode('-', rawUrlDecode($_GET['userSession']), 2);
    $_SESSION['page_vserver']['uSess']['id'] = (int)$id;
    $_SESSION['page_vserver']['uSess']['name'] = $name;
    unset(
        $_SESSION['page_vserver']['cid'],
        $_SESSION['page_vserver']['aclID'],
        $_SESSION['page_vserver']['groupID']
    );
}
/*
* Check for valid channel ID
*/
if (isset($_SESSION['page_vserver']['cid'])) {
    if (! isset($page->channelsList[$_SESSION['page_vserver']['cid']])) {
        $PMA->messageError('Murmur_InvalidChannelException');
        unset(
            $_SESSION['page_vserver']['cid'],
            $_SESSION['page_vserver']['aclID'],
            $_SESSION['page_vserver']['groupID']
        );
    }
}
/*
* Check for valid user session ID
*/
if (isset($_SESSION['page_vserver']['uSess'])) {
    if (! isset($page->onlineUsersList[$_SESSION['page_vserver']['uSess']['id']])) {
        /*
        * User is not online anymore, search for a reconnection.
        */
        foreach ($page->onlineUsersList as $user) {
            if ($user->name === $_SESSION['page_vserver']['uSess']['name']) {
                $_SESSION['page_vserver']['uSess']['id'] = $user->session;
                $new_session_found = true;
                break;
            }
        }
        if (! isset($new_session_found)) {
            $PMA->messageError('Murmur_InvalidSessionException');
            unset($_SESSION['page_vserver']['uSess']);
        }
    }
}
/*
* Default route :
* Root channel, this is the only thing we are sure to find in a vserver.
*/
if (! isset($_SESSION['page_vserver']['cid']) && ! isset($_SESSION['page_vserver']['uSess'])) {
    $_SESSION['page_vserver']['cid'] = 0;
}
/*
* Setup viewer state and get channel or user object.
*/
if (isset($_SESSION['page_vserver']['cid'])) {
    $page->viewerState = 'channel';
    $page->channelObj = clone $page->channelsList[$_SESSION['page_vserver']['cid']];
} else {
    $page->viewerState = 'user';
    $page->sessionObj = clone $page->onlineUsersList[$_SESSION['page_vserver']['uSess']['id']];
}

$viewerBoxWidget = new stdClass();
$viewerBoxWidget->type = 'widget';
$viewerBoxWidget->id = null;

/*
* Channel menu
*/
if ($PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
    if (isset($_SESSION['page_vserver']['cid'])) {

        $PMA->router->subtab->addRoute('acl');
        $PMA->router->subtab->addRoute('groups');
        $PMA->router->subtab->addRoute('properties');
        $PMA->router->subtab->setDefaultRoute('properties');
        $PMA->router->checkNavigation('subtab');
        /*
        * Count users currently in the channel
        */
        $usersInChannel = 0;
        foreach ($page->onlineUsersList as $user) {
            if ($user->channel === $page->channelObj->id) {
                ++$usersInChannel;
            }
        }
        /*
        * Menu : connection to channel
        */
        $menu = new channelsMenuStruc();
        $menu->text = $TEXT['conn_to_channel'];
        if ($page->channelObj->id > 0 && isset($page->connectionUrl)) {
            $menu->href = $page->connectionUrl->getChannelUrl($page->channelsList, $page->channelObj->id);
            $menu->img = PMA_IMG_CONN_16;
        }
        $page->actionMenu[] = $menu;
        /*
        * Menu : add sub-channel
        */
        $menu = new channelsMenuStruc();
        $menu->text = $TEXT['add_channel'];
        if (! $page->channelObj->temporary) {
            $menu->href = '?action=add_channel';
            $menu->img = PMA_IMG_ADD_16;
            $menu->js = 'onClick="return popup(\'channelAdd\');"';
            $PMA->popups->newHidden('channelAdd');
        }
        $page->actionMenu[] = $menu;
        /*
        * Menu : send a message
        */
        $menu = new channelsMenuStruc();
        $menu->href = '?action=messageToChannel';
        $menu->text = $TEXT['send_msg'];
        $menu->img = PMA_IMG_MSG_16;
        $menu->js = 'onClick="return popup(\'channelMessage\');"';
        $PMA->popups->newHidden('channelMessage');
        $page->actionMenu[] = $menu;
        /*
        * Menu : move users out the channel
        */
        $menu = new channelsMenuStruc();
        $menu->text = $TEXT['move_user_off_chan'];
        if ($usersInChannel > 0 && $totalChannels > 1) {
            $menu->href = '?action=move_users_out&amp;viewerAction';
            $menu->img = PMA_IMG_GO_UP_16;
        }
        $page->actionMenu[] = $menu;
        /*
        * Menu : Move users in the channel.
        */
        $menu = new channelsMenuStruc();
        $menu->text = $TEXT['move_user_in_chan'];
        if ($page->totalOnlineUsers > $usersInChannel) {
            $menu->href = '?action=move_users_in&amp;viewerAction';
            $menu->img = PMA_IMG_GO_UP_16;
            $menu->js = 'onClick="return popup(\'channelMoveIn\');"';
            $PMA->popups->newHidden('channelMoveIn');
        }
        $page->actionMenu[] = $menu;
        /*
        * Menu : link channel to others
        */
        $menu = new channelsMenuStruc();
        $menu->text = $TEXT['link_channel'];
        if ($totalChannels - count($page->channelObj->links) > 1) {
            $menu->href = '?action=link_channel&amp;viewerAction';
            $menu->img = 'images/tango/link_16.png';
        }
        $page->actionMenu[] = $menu;
        /*
        * Menu : unlink channel to others
        */
        $menu = new channelsMenuStruc();
        $menu->text = $TEXT['unlink_channel'];
        if (! empty($page->channelObj->links)) {
            $menu->href = '?action=unlink_channel&amp;viewerAction';
            $menu->img = PMA_IMG_CANCEL_16;
        }
        $page->actionMenu[] = $menu;
        /*
        * Menu : move channel
        */
        $menu = new channelsMenuStruc();
        $menu->text = $TEXT['move_channel'];
        if ($page->channelObj->id > 0) {
            $menu->href = '?action=move_channel&amp;viewerAction';
            $menu->img = PMA_IMG_GO_UP_16;
        }
        $page->actionMenu[] = $menu;
        /*
        * Menu : delete channel
        */
        $menu = new channelsMenuStruc();
        $menu->text = $TEXT['del_channel'];
        if ($page->channelObj->id > 0) {
            $menu->href = '?action=delete_channel';
            $menu->img = PMA_IMG_DELETE_16;
            $menu->js = 'onClick="return popup(\'channelDelete\');"';
            $PMA->popups->newHidden('channelDelete');
        }
        $page->actionMenu[] = $menu;

        /*
        * Setup widgets
        */
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'add_channel':
                    $viewerBoxWidget->type = 'popup';
                    $viewerBoxWidget->id = 'channelAdd';
                    break;
                case 'link_channel':
                    $viewerBoxWidget->type = 'widget';
                    $viewerBoxWidget->id = 'mumbleChannelsLink';
                    break;
                case 'unlink_channel':
                    $viewerBoxWidget->type = 'widget';
                    $viewerBoxWidget->id = 'mumbleChannelsUnlink';
                    break;
                case 'move_channel':
                    $viewerBoxWidget->type = 'widget';
                    $viewerBoxWidget->id = 'mumbleChannelsMove';
                    break;
                case 'add_group':
                    $viewerBoxWidget->type = 'popup';
                    $viewerBoxWidget->id = 'channelGroupAdd';
                    break;
                case 'delete_channel':
                    $viewerBoxWidget->type = 'popup';
                    $viewerBoxWidget->id = 'channelDelete';
                    break;
                case 'move_users_in':
                    $viewerBoxWidget->type = 'popup';
                    $viewerBoxWidget->id = 'channelMoveIn';
                    break;
                case 'move_users_out':
                    $viewerBoxWidget->type = 'popup';
                    $viewerBoxWidget->id = 'channelMoveOut';
                    break;
                case 'messageToChannel':
                    $viewerBoxWidget->type = 'popup';
                    $viewerBoxWidget->id = 'channelMessage';
                    break;
            }
        } else {
            switch($PMA->router->getRoute('subtab')) {
                case 'acl':
                    $viewerBoxWidget->type = 'widget';
                    $viewerBoxWidget->id = 'mumbleAclEditor';
                    break;
                case 'groups':
                    $viewerBoxWidget->type = 'widget';
                    $viewerBoxWidget->id = 'mumbleGroupEditor';
                    break;
                case 'properties':
                    $viewerBoxWidget->type = 'widget';
                    $viewerBoxWidget->id = 'mumbleChannelEditor';
                    break;
            }
        }
    /*
    * User menu
    */
    } elseif (isset($_SESSION['page_vserver']['uSess'])) {
        /*
        * Setup user ip.
        */
        $page->sessionObj->ip = PMA_ipHelper::decimalTostring($page->sessionObj->address);
        $page->sessionObj->ip = $page->sessionObj->ip['ip'];
        /*
        * Setup user certificate.
        */
        $certificatesList = $prx->getCertificateList($page->sessionObj->session);
        if (! empty($certificatesList)) {
            $page->sessionObj->certBlob = decimalArrayToChars($certificatesList[0]);
            $page->sessionObj->certSha1 = sha1($page->sessionObj->certBlob);
        } else {
            $page->sessionObj->certSha1 = 'No certificate found';
        }

        $PMA->router->subtab->addRoute('comment');
        $PMA->router->subtab->addRoute('certificate');
        $PMA->router->subtab->addRoute('infos');
        $PMA->router->subtab->setDefaultRoute('infos');
        $PMA->router->checkNavigation('subtab');

        /*
        * Menu : kick user
        */
        $menu = new channelsMenuStruc();
        $menu->href = '?action=kick_user';
        $menu->text = $TEXT['kick'];
        $menu->img = 'images/xchat/kick_16.png';
        $menu->js = 'onClick="return popup(\'userKick\');"';
        $PMA->popups->newHidden('userKick');
        $page->actionMenu[] = $menu;
        /*
        * Menu : ban user
        */
        if (isset($page->sessionObj->certBlob)) {
            $menu = new channelsMenuStruc();
            $menu->href = '?action=ban_user';
            $menu->text = $TEXT['ban'];
            $menu->img = 'images/xchat/ban_16.png';
            $menu->js = 'onClick="return popup(\'userBan\');"';
            $PMA->popups->newHidden('userBan');
            $page->actionMenu[] = $menu;
        }
        /*
        * Menu : modify session login.
        * Since murmur 1.2.4, it's possible to modify user session login
        */
        if ($PMA->murmurMeta->getVersionInt() >= 124) {
            $menu = new channelsMenuStruc();
            $menu->href = '?action=modifyUserSessionLogin';
            $menu->text = $TEXT['modify_user_session_name'];
            $menu->img = PMA_IMG_GROUP_16;
            $menu->js = 'onClick="return popup(\'userSessionLogin\');"';
            $PMA->popups->newHidden('userSessionLogin');
            $page->actionMenu[] = $menu;
        }
        /*
        * Menu : modify user comment
        */
        $menu = new channelsMenuStruc();
        $menu->href = '?action=modifyUserComment';
        $menu->text = $TEXT['modify_comment'];
        $menu->img = PMA_IMG_MUMBLE_COMMENT;
        $menu->js = 'onClick="return popup(\'userComment\');"';
        $PMA->popups->newHidden('userComment');
        $page->actionMenu[] = $menu;
        /*
        * Menu : Message to user
        */
        $menu = new channelsMenuStruc();
        $menu->href = '?action=messageToUser';
        $menu->text = $TEXT['send_msg'];
        $menu->img = PMA_IMG_MSG_16;
        $menu->js = 'onClick="return popup(\'userMessage\');"';
        $PMA->popups->newHidden('userMessage');
        $page->actionMenu[] = $menu;
        /*
        * Menu : move user
        */
        $menu = new channelsMenuStruc();
        $menu->text = $TEXT['move'];
        if ($totalChannels > 1) {
            $menu->href = '?action=move_user&amp;viewerAction';
            $menu->img = PMA_IMG_GO_UP_16;
        }
        $page->actionMenu[] = $menu;
        /*
        * Menu : mute user
        */
        $menu = new channelsMenuStruc();
        $menu->href = '?cmd=murmur_users_sessions&amp;muteUser';
        if ($page->sessionObj->mute) {
            $menu->text = $TEXT['unmute'];
            $menu->img = 'images/mumble/user_unmute.png';
        } else {
            $menu->text = $TEXT['mute'];
            $menu->img = PMA_IMG_MUMBLE_MUTED;
        }
        $page->actionMenu[] = $menu;
        /*
        * Menu : deafen user
        */
        $menu = new channelsMenuStruc();
        $menu->href = '?cmd=murmur_users_sessions&amp;deafUser';
        if ($page->sessionObj->deaf) {
            $menu->text = $TEXT['undeafen'];
            $menu->img = 'images/mumble/user_undeafen.png';
        } else {
            $menu->text = $TEXT['deafen'];
            $menu->img = PMA_IMG_MUMBLE_DEAFENED;
        }
        $page->actionMenu[] = $menu;
        /*
        * Menu : priority speaker
        */
        $menu = new channelsMenuStruc();
        $menu->href = '?cmd=murmur_users_sessions&amp;togglePrioritySpeaker';
        if ($page->sessionObj->prioritySpeaker) {
            $menu->text = $TEXT['disable_priority'];
            $menu->img = 'images/tango/microphone-muted_16.png';
        } else {
            $menu->text = $TEXT['enable_priority'];
            $menu->img = PMA_IMG_MUMBLE_MIC;
        }
        $page->actionMenu[] = $menu;
        /*
        * Menu : go to user registration
        */
        if ($page->sessionObj->userid >= 0) {
            $menu = new channelsMenuStruc();
            $menu->href = '?tab=registrations&amp;mumbleRegistration='.$page->sessionObj->userid;
            $menu->text = $TEXT['edit_account'];
            $menu->img = PMA_IMG_EDIT_16;
            $page->actionMenu[] = $menu;
        /*
        * Menu : add user registration
        */
        } elseif (isset($page->sessionObj->certBlob)) {
            $menu = new channelsMenuStruc();
            $menu->href = '?cmd=murmur_users_sessions&amp;register_session';
            $menu->text = $TEXT['register_user'];
            $menu->img = PMA_IMG_ADD_16;
            $page->actionMenu[] = $menu;
        }

        /*
        * Setup widgets
        */
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'kick_user':
                    $viewerBoxWidget->type = 'popup';
                    $viewerBoxWidget->id = 'userKick';
                    break;
                case 'ban_user':
                    $viewerBoxWidget->type = 'popup';
                    $viewerBoxWidget->id = 'userBan';
                    break;
                case 'modifyUserSessionLogin':
                    $viewerBoxWidget->type = 'popup';
                    $viewerBoxWidget->id = 'userSessionLogin';
                    break;
                case 'modifyUserComment':
                    $viewerBoxWidget->type = 'popup';
                    $viewerBoxWidget->id = 'userComment';
                    break;
                case 'messageToUser':
                    $viewerBoxWidget->type = 'popup';
                    $viewerBoxWidget->id = 'userMessage';
                    break;
                case 'move_user':
                    $viewerBoxWidget->type = 'widget';
                    $viewerBoxWidget->id = 'mumbleUserMove';
                    break;
            }
        } else {
            switch($PMA->router->getRoute('subtab')) {
                case 'infos':
                    $viewerBoxWidget->type = 'widget';
                    $viewerBoxWidget->id = 'mumbleUserInfos';
                    break;
                case 'certificate':
                    $viewerBoxWidget->type = 'widget';
                    $viewerBoxWidget->id = 'mumbleUserCertificate';
                    break;
                case 'comment':
                    $viewerBoxWidget->type = 'widget';
                    $viewerBoxWidget->id = 'mumbleUserComment';
                    break;
            }
        }
    }
}

if ($PMA->user->is(PMA_USER_MUMBLE)) {
    $viewerBoxWidget->type = 'widget';
    $viewerBoxWidget->id = 'mumbleSelfUser';
}

if ($viewerBoxWidget->type === 'widget') {
    $PMA->widgets->newModule($viewerBoxWidget->id);
    $page->viewerBoxWidgetPath = $PMA->widgets->getViewPath($viewerBoxWidget->id);
} else {
    $PMA->popups->newModule($viewerBoxWidget->id);
    $page->viewerBoxWidgetPath = $PMA->widgets->getViewPath($viewerBoxWidget->id);
}

/*
* Setup the channel viewer for admins and mumble users.
*/
if ($PMA->user->is(PMA_USER_MUMBLE)) {
    /*
    * Mumble user viewer.
    */
    $viewer = new PMA_MurmurViewer();
    $viewer->enableOption('channelSelection');
    $viewer->enableOption('usersSelection');

} elseif ($PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
    /*
    * Admins viewer.
    */
    $viewer = new PMA_MurmurViewerAdmin();
    $viewer->enableOption('channelSelection');
    $viewer->enableOption('usersSelection');
    /*
    * Actions with the viewer.
    */
    if (isset($_GET['viewerAction'])) {

        $viewer->css = 'action';
        $viewer->disableOption('usersSelection');

        switch($_GET['action']) {
            case 'move_user':
                $user = $page->onlineUsersList[$_SESSION['page_vserver']['uSess']['id']];
                $viewer->setParam('channelHREF', '?cmd=murmur_users_sessions&amp;move_user_to');
                // Disable selected user channel.
                $viewer->disableChannelID($user->channel);
                break;
           case 'move_users_out':
                // Disable to select current channel.
                $viewer->disableChannelID($_SESSION['page_vserver']['cid']);
                $viewer->setParam('channelHREF', '?action=move_users_out&amp;viewerAction&amp;to');
                if (isset($_GET['to']) && ctype_digit($_GET['to'])) {
                    // Disable to select all channels.
                    $viewer->disableOption('channelSelection');
                    $viewer->setParam('selectedMoveTo', (int)$_GET['to']);
                }
                break;
            case 'move_users_in':
                $viewer->disableOption('channelSelection');
                break;
            case 'move_channel':
                $chan = $page->channelsList[$_SESSION['page_vserver']['cid']];
                $viewer->setParam('channelHREF', '?cmd=murmur_channel&amp;move_channel_to');
                $allChildrensID = $viewer->getAllChildrensID($getTree, $_SESSION['page_vserver']['cid']);
                // Disable to select current, parent and all childrens channels
                $viewer->disableChannelID($chan->id);
                $viewer->disableChannelID($chan->parent);
                foreach ($allChildrensID as $id) {
                    $viewer->disableChannelID($id);
                }
                break;
            case 'link_channel':
                $chan = $page->channelsList[$_SESSION['page_vserver']['cid']];
                $viewer->setParam('channelHREF', '?cmd=murmur_channel&amp;link_channel');
                // Disable to select current, and all direct link channels
                foreach ($chan->links as $id) {
                    $viewer->disableChannelID($id);
                }
                $viewer->disableChannelID($chan->id);
                break;
            case 'unlink_channel':
                $chan = $page->channelsList[$_SESSION['page_vserver']['cid']];
                $viewer->setParam('channelHREF', '?cmd=murmur_channel&amp;unlink_channel');
                // Disable all channels which are not directs links
                foreach ($page->channelsList as $id => $obj) {
                    if (! in_array($id, $chan->links, true)) {
                        $viewer->disableChannelID($id);
                    }
                }
                break;
        }
    }
}

/*
* Common setup for admins and mumble users.
*/
$viewer->setParam('serverName', $page->vserverName);
$viewer->setParam('defaultChanID', $page->defaultChannelID);
$viewer->enableOption('showStatusIcons');
$viewer->enableOptionShowPasswords($prx);
$viewer->enableOption('showChannelsLinks');
/*
* Setup the selected channel or user session.
*/
if (isset($_SESSION['page_vserver']['cid'])) {
    $viewer->setParam('selectedChanID', $_SESSION['page_vserver']['cid']);
    $viewer->setupSelectedChannelLinks($page->channelsList);
} elseif (isset($_SESSION['page_vserver']['uSess'])) {
    $viewer->setParam('selectedUserSessID', $_SESSION['page_vserver']['uSess']['id']);
}
