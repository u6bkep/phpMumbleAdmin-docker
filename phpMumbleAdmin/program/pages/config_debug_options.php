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

$page->debug = $PMA->config->get('debug');
$page->set('langFlag', $PMA->config->get('debug_select_flag'));
$page->set('debugStats', $PMA->config->get('debug_stats'));
$page->set('debugMessages', $PMA->config->get('debug_messages'));
$page->set('debugSession', $PMA->config->get('debug_session'));
$page->set('pmaObject', $PMA->config->get('debug_object'));
