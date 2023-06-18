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

$page->set('profileName', $PMA->userProfile['name']);
$page->set('isPublic', $PMA->userProfile['public']);
$page->set('host', $PMA->userProfile['host']);
$page->set('port', $PMA->userProfile['port']);
$page->set('timeout', $PMA->userProfile['timeout']);
$page->set('secret', $PMA->userProfile['secret']);
$page->set('httpAddr', $PMA->userProfile['http-addr']);
/*
* Setup connection.
*/
require PMA_DIR_INCLUDES.'iceConnection.inc';
/*
* Setup Iceinfos.
*/
$page->IceInfos = array();
/*
* Php Ice version.
*/
$page->IceInfos[] = array('php-Ice', PMA_ICE_VERSION_STR);
/*
* Get murmur version.
*/
$version = $PMA->murmurMeta->isConnected() ?
    $PMA->murmurMeta->getVersionFull() : 'Not connected';
$page->IceInfos[] = array('Murmur', $version);
/*
* Default profile button
*/
$page->addDefaultButton = (
    $PMA->murmurMeta->isConnected()
    && $PMA->userProfile['public'] === true
    && $PMA->userProfile['id'] !== $PMA->config->get('default_profile')
);
/*
* Delete profile button
*/
$page->addDeleteProfileButton = ($PMA->profiles->total() > 1);
/*
* Get slices php profiles list.
*/
$page->slicesPhpProfiles = array();

$dir = PMA_DIR_SLICE_PHP;
$custom = PMA_DIR_SLICE_PHP_CUSTOM;

/*
* Scan directories.
*/
if (is_dir($dir) && is_readable($dir)) {
    $scan = scanDir($dir);
    if (is_dir($custom) && is_readable($custom)) {
        $scan2 = scanDir($custom);
        if (! empty($scan2)) {
            $scan = array_merge($scan, $scan2);
        }
    }
    foreach ($scan as $filename) {
        if (substr($filename, -4) === '.php') {
            $name = substr($filename, 0, -4);
            $option = new stdClass();
            $option->name = $name;
            $option->filename = $filename;
            $option->select = ($PMA->userProfile['slice_php'] === $filename);
            $page->slicesPhpProfiles[] = $option;
        }
    }
}
/*
* Setup JS popups
*/
$PMA->popups->newHidden('profileAdd');
if ($page->addDeleteProfileButton) {
    $PMA->popups->newHidden('profileDelete');
}
