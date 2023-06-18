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

$PMA->router->tab->addRoute('admins');
$PMA->router->tab->addRoute('bans');
$PMA->router->tab->addRoute('pwRequests');
$PMA->router->tab->addRoute('whosOnline');
$PMA->router->tab->addRoute('logs');
$PMA->router->checkNavigation('tab');

switch($PMA->router->getRoute('tab')) {

    case 'admins':

        $MiscNav = $PMA->router->controlMiscNavigation('adminRegistration');

        $PMA->page->addCommonController('administration_admins');

        if (isset($_GET['add_admin'])) {
            $PMA->popups->newModule('adminAdd');
        } elseif (isset($_GET['remove_admin'])) {
            $PMA->popups->newModule('adminDelete');
        } elseif (! is_null($MiscNav)) {
            $PMA->page->addCommonController('administration_admins_registration');
            if (isset($_GET['adminRegistrationEditor'])) {
                $PMA->popups->newModule('adminsRegistrationEditor');
            } else {
                $PMA->page->enable('administration_admins_registration');
            }
        } else {
            // Default : admins table
            $PMA->page->enable('administration_admins_table');
        }
    break;

    case 'bans':
        $PMA->page->enable('administration_pmaBans');
    break;

    case 'pwRequests':
        $PMA->page->enable('administration_pwRequests');
    break;

    case 'whosOnline':
        $PMA->page->enable('administration_whosOnline');
    break;

    case 'logs':
        $PMA->router->subtab->addRoute('all');
        $PMA->router->subtab->addRoute('PMA');
        $PMA->router->subtab->addRoute('auth');
        $PMA->router->subtab->addRoute('pwGen');
        $PMA->router->subtab->addRoute('autoBan');
        $PMA->router->subtab->addRoute('smtp');
        $PMA->router->subtab->addRoute('action');
        $PMA->router->checkNavigation('subtab');
        $PMA->page->enable('administration_pmaLogs');
    break;
}
