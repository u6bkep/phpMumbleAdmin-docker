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

$page->extViewerEnable = $PMA->config->get('external_viewer_enable');
$page->set('enable', $PMA->config->get('external_viewer_enable'));
$page->set('path', PMA_HTTP_HOST.PMA_HTTP_PATH);
$page->set('id', $PMA->router->getRoute('profile'));
$page->set('width', $PMA->config->get('external_viewer_width'));
$page->set('height', $PMA->config->get('external_viewer_height'));
$page->set('vertical', $PMA->config->get('external_viewer_vertical'));
$page->set('scroll', $PMA->config->get('external_viewer_scroll'));

