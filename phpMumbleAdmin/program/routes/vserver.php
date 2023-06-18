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
* Server ID
*/
if (isset($_GET['sid']) && ctype_digit($_GET['sid']) && $PMA->user->isMinimum(PMA_USER_ADMIN)) {
    $_GET['sid'] =  (int)$_GET['sid'];
    if (isset($_SESSION['page_vserver']['id']) && $_SESSION['page_vserver']['id'] !== $_GET['sid']) {
        unset($_SESSION['page_vserver']);
        //$PMA->router->removeHistory('page', 'vserver');
    }
    $_SESSION['page_vserver']['id'] = $_GET['sid'];
}

/*
* Common controller
*/
$PMA->page->addCommonController('vserver');
/*
* Tabs
*/
if ($PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
    $PMA->router->tab->addRoute('channels');
    $PMA->router->tab->addRoute('settings');
    $PMA->router->tab->addRoute('settingsMore');
    $PMA->router->tab->addRoute('registrations');
    $PMA->router->tab->addRoute('bans');
    if ($PMA->user->isMinimum(PMA_USER_ROOTADMIN) OR $PMA->config->get('vlogs_admins_active')) {
        $PMA->router->tab->addRoute('logs');
    }
} else {
    $PMA->router->tab->addRoute('channels');
    $PMA->router->tab->addRoute('registrations');
}
$PMA->router->checkNavigation('tab');

/*
* Routes:
* Priority to "confirmStopServer"
*/
if (isset($_GET['confirmStopSrv'])) {
    $PMA->page->enable('confirmStopServer');
} else {
    switch($PMA->router->getRoute('tab')) {

        case 'channels':
            $PMA->page->enable('vserver_channels');
            break;

        case 'settings':
            $PMA->page->enable('vserver_settings');
            break;

        case 'settingsMore':

            $PMA->page->addCommonController('vserver_settingsMore');

            $PMA->router->subtab->addRoute('welcometext');
            $PMA->router->subtab->addRoute('certificate');
            if ($PMA->user->is(PMA_USER_SUPERADMIN)) {
                $PMA->router->subtab->addRoute('PEM');
            }
            $PMA->router->checkNavigation('subtab');

            if (isset($_GET['resetCertificate'])) {
                $PMA->popups->newModule('settingsResetCertificate');
            } else {
                switch($PMA->router->getRoute('subtab')) {
                    case 'welcometext':
                        $PMA->page->enable('vserver_settingsMore_welcometext');
                        break;
                    case 'certificate':
                        $PMA->page->enable('vserver_settingsMore_certificate');
                        break;
                    case 'PEM':
                        $PMA->page->enable('vserver_settingsMore_certificatePem');
                        break;
                }
            }
            break;

        case 'registrations':

            $PMA->page->addCommonController('vserver_registrations');

            $MiscNav = $PMA->router->controlMiscNavigation('mumbleRegistration');
            // Mumble users must get their own registration
            if ($PMA->user->is(PMA_USER_MUMBLE)) {
                $MiscNav = $PMA->user->mumbleUID;
            }

            if (is_int($MiscNav)) {
                if (isset($_GET['delete_account'])) {
                    $PMA->popups->newModule('mumbleRegistrationDelete');
                } elseif (isset($_GET['editMumbleRegistration'])) {
                    $PMA->popups->newModule('mumbleRegistrationEditor');
                } elseif (isset($_GET['remove_avatar'])) {
                    $PMA->popups->newModule('mumbleRegistrationDeleteAvatar');
                } else {
                    $PMA->page->enable('vserver_registrations_userCard');
                }
            } elseif (isset($_GET['addMumbleAccount'])) {
                $PMA->popups->newModule('mumbleRegistrationAdd');
            } elseif (isset($_GET['deleteMumbleAccountID'])) {
                $PMA->popups->newModule('mumbleRegistrationDeleteID');
            } else {
                $PMA->page->enable('vserver_registrations_table');
            }
            break;

        case 'bans':

            $PMA->page->addCommonController('vserver_bans');

            if (isset($_GET['addBan'])) {
                $PMA->page->enable('vserver_bans_add');
            } elseif (isset($_GET['edit_ban_id'])) {
                $PMA->page->enable('vserver_bans_editor');
            } elseif (isset($_GET['delete_ban_id'])) {
                $PMA->popups->newModule('bansDelete');
            } else {
                $PMA->page->enable('vserver_bans_table');
            }
            break;

        case 'logs':
            $PMA->page->enable('vserver_logs');
            break;
    }
}

