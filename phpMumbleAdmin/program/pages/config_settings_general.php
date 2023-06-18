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

$page->set('siteTitle', $PMA->config->get('siteTitle'));
$page->set('siteComment', $PMA->config->get('siteComment'));
$page->set('logout', $PMA->config->get('auto_logout'));
$page->set('updateCheck', $PMA->config->get('update_check'));
$page->set('murmurVersion', $PMA->config->get('murmur_version_url'));
$page->set('ddlAuthPage', $PMA->config->get('ddl_auth_page'));
$page->set('ddlRefresh', $PMA->config->get('ddl_refresh'));
$page->set('ddlShowCacheUptime', $PMA->config->get('ddl_show_cache_uptime'));
$page->set('showAvatarSa', $PMA->config->get('show_avatar_sa'));
$page->set('IcePhpIncludePath', $PMA->config->get('IcePhpIncludePath'));
$page->set('IcePhpIncludePathInfos', get_include_path());
