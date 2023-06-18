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
* *******************************************
* Common controller for vserver pages.
* *******************************************
*/

/*
* Server ID
*/
// if (isset($_GET['sid']) && ctype_digit($_GET['sid'])) {
//     $_GET['sid'] =  (int)$_GET['sid'];
//     if (isset($_SESSION['page_vserver']['id']) && $_SESSION['page_vserver']['id'] !== $_GET['sid']) {
//         unset($_SESSION['page_vserver']);
//         if ($PMA->router->page->isNewUserRoute()) {
//             // Keep tab if we use vserver navigation.
//             $PMA->router->tab->setCurrentRoute('channels');
//         }
//     }
//     $_SESSION['page_vserver']['id'] = $_GET['sid'];
// }

/*
* Force SuperUsers and registered users to access to their own vserver.
*/
if ($PMA->user->is(PMA_USERS_MUMBLE)) {
    $_SESSION['page_vserver']['id'] = $PMA->user->mumbleSID;
}
/*
* Setup server ID.
*/
$page->vserverID = $_SESSION['page_vserver']['id'];
/*
* Check if an admin has access to the current server id.
*/
if ($PMA->user->is(PMA_USER_ADMIN)) {
    if (! $PMA->user->checkServerAccess($page->vserverID)) {
        $PMA->router->page->setCurrentRoute('overview');
        $PMA->message('Access_denied_to_vserver '.$page->vserverID);
        unset($_SESSION['page_vserver']);
        $PMA->redirection();
    }
}
/*
* Setup Ice connection.
*/
require PMA_DIR_INCLUDES.'iceConnection.inc';
if (! $PMA->murmurMeta->isConnected()) {
    throw new PMA_pageException('ice_error_common');
}

/*
* Setup $prx.
*/
if (is_null($prx = $PMA->murmurMeta->getServer($page->vserverID))) {
    if ($PMA->user->isMinimum(PMA_USER_ADMIN)) {
        $PMA->router->page->setCurrentRoute('overview');
        unset($_SESSION['page_vserver']);
    } else {
        // Logout mumble users
        $PMA->logout();
    }
    $PMA->messageError('Murmur_InvalidServerException');
    $PMA->redirection();
}
/*
* Setup vserver name.
* Setup booted flag.
*/
$page->vserverName = $prx->getParameter('registername');
$page->vserverIsBooted = $prx->isRunning();
/*
* Setup SuperUserRu rights.
* Get online user list.
*/
if ($page->vserverIsBooted) {
    if ($PMA->user->is(PMA_USERS_REGISTERED)) {
        if ($PMA->config->get('SU_auth') && $PMA->config->get('SU_ru_active')) {
            $prx->getACL(0, $aclList, $groupList, $inherit);
            // Memo: redirection is required on class update.
            if (PMA_MurmurAclHelper::isSuperUserRu($PMA->user->mumbleUID, $aclList)) {
                if ($PMA->user->is(PMA_USER_MUMBLE)) {
                    $PMA->user->setClass(PMA_USER_SUPERUSER_RU);
                    $PMA->redirection();
                }
            } else {
                if ($PMA->user->is(PMA_USER_SUPERUSER_RU)) {
                    $PMA->user->setClass(PMA_USER_MUMBLE);
                    $PMA->redirection();
                }
            }
        } else {
            if ($PMA->user->is(PMA_USER_SUPERUSER_RU)) {
                $PMA->user->setClass(PMA_USER_MUMBLE);
                $PMA->redirection();
            }
        }
    }
    /*
    * Get online user list.
    */
    $page->onlineUsersList = $prx->getUsers();
    $page->totalOnlineUsers = count($page->onlineUsersList);
}
/*
* Setup infoPanel
*/
$page->showInfoPanel = ($PMA->cookie->get('infoPanel') OR ! $page->vserverIsBooted);
if ($page->showInfoPanel) {
    /*
    * InfoPanel : Start / stop button
    */
    if (
        $PMA->user->isMinimum(PMA_USER_ADMIN) OR
        $PMA->user->is(PMA_USERS_SUPERUSERS) && $PMA->config->get('SU_start_vserver')
    ) {
        if ($page->vserverIsBooted) {
            $title = $TEXT['srv_active'];
            $src = PMA_IMG_ONLINE_16;
        } else {
            $title = '';
            $src = PMA_IMG_OFFLINE_16;
        }
        $button = '<a href="?cmd=overview&amp;toggle_server_status='.$page->vserverID.'" title="'.$title.'"><img src="'.$src.'" class="button" alt="" /></a>';
        $PMA->infoPanel->add($button);
        if (! $page->vserverIsBooted) {
            $PMA->infoPanel->addFillOccas($TEXT['srv_inactive']);
        }
    }
    if ($page->vserverIsBooted) {
        /*
        * InfoPanel : connection button.
        */
        $page->connectionUrl = new PMA_MurmurUrl();
        $page->connectionUrl->setCustomLogin($PMA->cookie->get('vserver_login'));
        $page->connectionUrl->setDefaultLogin($PMA->user->login);
        $page->connectionUrl->setGuestLogin('Guest_'.genRandomChars(5));
        $page->connectionUrl->setServerPassword($prx->getParameter('password'));
        $page->connectionUrl->setCustomHttpAddr($PMA->userProfile['http-addr']);
        $page->connectionUrl->setDefaultHttpAddr($prx->getParameter('host'));
        $page->connectionUrl->setPort($prx->getParameter('port'));
        if ($PMA->config->get('murmur_version_url')) {
            $page->connectionUrl->setMurmurVersion($PMA->murmurMeta->getVersionString());
        }
        $url = $page->connectionUrl->getUrl();
        $button = '<a href="'.$url.'"><img src="'.PMA_IMG_CONN_16.'" class="button" title="'.$TEXT['conn_to_srv'].'" alt="" /></a>';
        $PMA->infoPanel->addRight($button);
        /*
        * InfoPanel : Get server uptime and started at.
        */
        $uptime = $prx->getUptime();
        if (is_int($uptime)) {
            $ts = time() - $uptime;
            $date = $DATES->strDate($ts);
            $time = $DATES->strTime($ts);
            $title = sprintf($TEXT['started_at'], $date, $time);
            $uptime = PMA_datesHelper::uptime($uptime);
            $uptime = '<span class="tHelp" title="'.$title.'">'.$uptime.'</span>';
            $uptime = sprintf($TEXT['fill_uptime'], $uptime);
            $PMA->infoPanel->addFill($uptime);
        }
        /*
        * InfoPanel : total of users.
        */
        $total = $page->totalOnlineUsers;
        $max = $prx->getParameter('users');
        $flag = pmaGetGaugeFlag($total, $max);
        $users = sprintf($TEXT['fill_users'], '<strong class="gauge '.$flag.'">'.$total.'</strong> / '.$max);
        $PMA->infoPanel->addFill($users);
    }
}
/*
* Error message if the vserver is not booted.
*/
if (! $page->vserverIsBooted && $PMA->user->is(PMA_USER_MUMBLE)) {
    $PMA->messageError('vserver_offline');
}
