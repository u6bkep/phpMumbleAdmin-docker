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

$page->set('overview', $PMA->config->get('table_overview'));
$page->set('users', $PMA->config->get('table_users'));
$page->set('bans', $PMA->config->get('table_bans'));

$page->set('totalOnline', $PMA->config->get('show_online_users'));
$page->set('totalOnlineSa', $PMA->config->get('show_online_users_sa'));
$page->set('uptime', $PMA->config->get('show_uptime'));
$page->set('uptimeSa', $PMA->config->get('show_uptime_sa'));
