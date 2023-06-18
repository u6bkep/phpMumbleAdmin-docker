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

$PMA->page->addCommonController('overview');

if (isset($_GET['confirmStopSrv'])) {
    $PMA->page->enable('confirmStopServer');
} elseif (isset($_GET['addServer'])) {
    $PMA->popups->newModule('serverAdd');
} elseif (isset($_GET['messageToServers'])) {
    $PMA->popups->newModule('serversMessage');
} elseif (isset($_GET['deleteServer'])) {
    $PMA->popups->newModule('serverDelete');
} elseif (isset($_GET['resetServer'])) {
    $PMA->popups->newModule('serverReset');
} elseif (isset($_GET['murmurDefaultConf'])) {
    $PMA->popups->newModule('murmurDefaultConf');
} elseif (isset($_GET['murmurMassSettings'])) {
    $PMA->popups->newModule('murmurMassSettings');
} else {
    $PMA->page->enable('overview_table');
}
