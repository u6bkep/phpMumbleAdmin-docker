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
* Check for auto-update.
*/
if ($PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
    $updates = new PMA_updates();
    $updates->setCacheParameters($PMA->app->get('updates'));
    if ($updates->isAutoCheckRequired($PMA->config->get('update_check'))) {
        $PMA->debug('Auto-check PMA updates requested.');
        if ($updates->check()) {
            $PMA->message(array('new_pma_version', $updates->get('current_version')));
        }
        $PMA->app->set('updates', $updates->getCacheParameters());
    }
}
/*
* Get ICE connection
*/
require PMA_DIR_INCLUDES.'iceConnection.inc';
if (! $PMA->murmurMeta->isConnected()) {
    throw new PMA_pageException('ice_error_common');
}
/*
* Get servers datas from $prx
*/
$page->allServers = $PMA->murmurMeta->getAllServers();
$page->allBootedServers = $PMA->murmurMeta->getBootedServers();
/*
* Admins sanity:
* remove servers that an admin dont have access.
*/
if ($PMA->user->is(PMA_USER_ADMIN)) {
    $all = array();
    $booted = array();
    foreach ($page->allServers as $prx) {
        if ($PMA->user->checkServerAccess($prx->getSid())) {
            $all[] = $prx;
            if (in_array($prx, $page->allBootedServers)) {
                $booted[] = $prx;
            }
        }
    }
    $page->allServers = $all;
    $page->allBootedServers = $booted;
}
/*
* Get murmur version.
*/
$murmur = sprintf($TEXT['murmur_vers'], $PMA->murmurMeta->getVersionString());
/*
* Get uptime and "started at".
*/
$uptime = $PMA->murmurMeta->getUptime();
if (is_int($uptime)) {
    $ts = time() - $uptime;
    $date = $DATES->strDate($ts);
    $time = $DATES->strTime($ts);
    $uptime = PMA_datesHelper::uptime($uptime);
    $uptime = ' (<span class="tHelp" title="'.sprintf($TEXT['started_at'], $date, $time).'">'.$uptime.'</span>)';
}
/*
* Count servers.
*/
$totalAll = count($page->allServers);
$totalBooted = count($page->allBootedServers);
$totalServers = sprintf($TEXT['total_srv'], '<mark>'.$totalBooted.'</mark> / '.$totalAll);
/*
* Add to $prx key and isBooted values for sorting.
*/
$page->showOnlineUsers =
    $PMA->config->get('show_online_users') &&
    ($PMA->user->isMinimum(PMA_USER_ROOTADMIN) OR ! $PMA->config->get('show_online_users_sa'));
if ($page->showOnlineUsers) {
    $totalUsers = 0;
}
foreach ($page->allServers as $key => $prx) {
    $prx->isBooted = in_array($prx, $page->allBootedServers);
    $prx->key = $key;
    if ($prx->isBooted && $page->showOnlineUsers) {
        $prx->users = count($prx->getUsers());
        $totalUsers += $prx->users;
    }
}
if ($page->showOnlineUsers) {
    $totalUsers = sprintf($TEXT['total_users'], '<mark>'.$totalUsers.'</mark>');
}
/*
* Setup info panel.
*/
$PMA->infoPanel->addFill($murmur.$uptime);
$PMA->infoPanel->addFill($totalServers);
if (isset($totalUsers)) {
    $PMA->infoPanel->addFill($totalUsers);
}
if (isset($updates) && $updates->get('new_version')) {
    $text = sprintf($TEXT['pma_available'], $updates->get('current_version'));
    $isUpdate = '<a href="'.PMA_PROJECT_URL.'">'.$text.'</a>';
    $PMA->infoPanel->addFillRight($isUpdate);
}
