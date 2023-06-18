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

$PMA->page->addCommonController('install');

$PMA->router->tab->addRoute('start');
$PMA->router->tab->addRoute('requirement');
$PMA->router->tab->addRoute('setup_SuperAdmin');
$PMA->router->checkNavigation('tab');

switch($PMA->router->getRoute('tab')) {
    case 'setup_SuperAdmin':
        $PMA->page->enable('install_setupSuperAdmin');
    break;
    case 'requirement':
        $PMA->page->enable('install_requirement');
    break;
    case 'start':
        $PMA->page->enable('install_start');
    break;
}
