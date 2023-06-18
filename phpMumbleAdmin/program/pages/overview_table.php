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

$PMA->widgets->newModule('tablePagingMenu');
/*
* Setup captions.
*/
$PMA->captions->addButton(PMA_IMG_ONLINE_16,         $TEXT['srv_active']);
$PMA->captions->addButton(PMA_IMG_OFFLINE_16,        $TEXT['srv_inactive']);
$PMA->captions->addButton(PMA_IMG_RESET_16,          $TEXT['reset_srv_info']);
$PMA->captions->addButton(PMA_IMG_CONN_16,           $TEXT['conn_to_srv']);
$PMA->captions->addButton(PMA_IMG_UNLOCKED_16,       $TEXT['webaccess_on']);
$PMA->captions->addButton(PMA_IMG_LOCKED_16,         $TEXT['webaccess_off']);
$PMA->captions->addButton(PMA_IMG_TRASH_16,          $TEXT['del_srv']);
/*
* Setup parameters.
*/
$page->defaultSettingsButton = $PMA->user->isMinimum(PMA_USER_ROOTADMIN);
$page->addServerButton = $PMA->user->isMinimumAdminFullAccess();
$page->sendMessageButton = $PMA->user->isMinimum(PMA_USER_ADMIN);
$page->userCanDelete = $PMA->user->isMinimumAdminFullAccess();
/*
* Setup popups
*/
if ($page->defaultSettingsButton) {
    $PMA->popups->newHidden('murmurDefaultConf');
    $PMA->popups->newHidden('murmurMassSettings');
}
if ($page->addServerButton) {
    $PMA->popups->newHidden('serverAdd');
}
if ($page->sendMessageButton) {
    $PMA->popups->newHidden('serversMessage');
}
$PMA->popups->newHidden('serverReset');
if ($page->userCanDelete) {
    $PMA->popups->newHidden('serverDelete');
}
/*
* Setup common parameters.
*/
$connectionUrl = new PMA_MurmurUrl();
$connectionUrl->setCustomLogin($PMA->cookie->get('vserver_login'));
$connectionUrl->setDefaultLogin($PMA->user->login);
$connectionUrl->setGuestLogin('Guest_'.genRandomChars(5));
$connectionUrl->setCustomHttpAddr($PMA->userProfile['http-addr']);
if ($PMA->config->get('murmur_version_url')) {
    $connectionUrl->setMurmurVersion($PMA->murmurMeta->getVersionString());
}

$showUptime =
(
    $PMA->config->get('show_uptime') &&
    ($PMA->user->isMinimum(PMA_USER_ROOTADMIN) OR ! $PMA->config->get('show_uptime_sa'))
);

$hasSid = isset($_SESSION['page_vserver']['id']);
/*
* Setup table.
*/
$page->table = new PMA_table($page->allServers);

$page->table->setMaxPerPage($PMA->config->get('table_overview'));
$page->table->setNavigation($PMA->router->getTableNavigation('key'));

$page->table->sortDatas();
$page->table->pagingDatas();
$page->table->getMinimumLines();

$time = time();
foreach ($page->table->datas as &$prx) {

    if (is_object($prx)) {

        $data = new stdClass();

        $data->id = $prx->getSid();
        $data->isBooted = $prx->isBooted;
        $data->selected =
        (
            $hasSid && $_SESSION['page_vserver']['id'] === $data->id
        );
        // Encode server name once.
        $data->serverNameEnc = htEnc($prx->getParameter('registername'));
        $data->host = $prx->getParameter('host');
        $data->port = $prx->getParameter('port');

        if (PMA_ipHelper::isIPv6($data->host)) {
            $data->host = '['.$data->host.']';
        }
        $data->uptime = '';
        if ($showUptime && $data->isBooted) {
            $uptime = $prx->getUptime();
            $started = $time - $uptime;
            $data->uptime = PMA_datesHelper::uptime($uptime);
            $data->date = $DATES->strDate($started);
            $data->time = $DATES->strTime($started);
        }
        $data->connURL = '';
        if ($data->isBooted) {
            $connectionUrl->url = null; // reset
            $connectionUrl->setServerPassword($prx->getParameter('password'));
            $connectionUrl->setDefaultHttpAddr($prx->getParameter('host'));
            $connectionUrl->setPort($prx->getParameter('port'));
            $data->connURL = $connectionUrl->getUrl();
        }
        $data->gauge = '';
        if (isset($prx->users)) {
            $data->users = $prx->users;
            $data->max = $prx->getParameter('users');
            $data->gauge = pmaGetGaugeFlag($data->users, $data->max);
        }
        $data->webAccess = ($prx->getParameter('PMA_permitConnection') === 'true');
        /*
        * Replace...
        */
        $prx = $data;
    }
}

$page->table->sortColumn('isBooted', 'S', true);
$page->table->sortColumn('key', 'id', true);

