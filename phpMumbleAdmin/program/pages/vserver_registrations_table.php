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

$PMA->popups->newHidden('mumbleRegistrationAdd');
$PMA->popups->newHidden('mumbleRegistrationDeleteID');
$PMA->widgets->newModule('tablePagingMenu');
/*
* Setup captions.
*/
$PMA->captions->addButton(PMA_IMG_ONLINE_16,    $TEXT['user_is_online']);
$PMA->captions->addButton(PMA_IMG_OFFLINE_16,   $TEXT['offline']);
$PMA->captions->add(PMA_IMG_MUMBLE_COMMENT,     $TEXT['have_a_comm']);
$PMA->captions->add(PMA_IMG_OK_16,              $TEXT['have_a_cert']);
$PMA->captions->addButton(PMA_IMG_TRASH_16,     $TEXT['delete_acc']);
/*
* Setup table.
*/
$page->table = new PMA_table_murmurRegistrations($page->registredUsers);

$page->table->setMaxPerPage($PMA->config->get('table_users'));
$page->table->setNavigation($PMA->router->getTableNavigation('uid'));

if (isset($_SESSION['search']['registrations'])) {
    // Search only for users logins.
    $page->table->search($_SESSION['search']['registrations']);
}

$time = time();
foreach ($page->table->datas as $uid => &$login) {

    $registration = $prx->getRegistration($uid);
    $registration = new PMA_ctrler_mumbleRegistrationObject($registration);
    $status = $page->usersStatus->check($uid);

    $data = new stdClass();

    $data->status = $status->isOnline;
    $data->statusURL = $status->sessionUrl;
    $data->uid = $uid;
    $data->login = $login;
    $data->loginEnc = htEnc($login);
    $data->isRenamedSuperUser = ($uid === 0 && strtolower($login) !== 'superuser');
    $data->email = $registration->email;
    $data->emailEnc = htEnc($registration->email);
    $data->hasComment = ($registration->desc !== '');
    $data->hasHash = ($registration->cert !== '');
    $data->lastActivity = '';
    $data->lastActivityUptime = '';
    $data->lastActivityDate = '';
    if ($registration->lastActivity !== '') {
        $data->lastActivity = PMA_datesHelper::datetimeToTimestamp($registration->lastActivity);
        $data->lastActivityUptime = PMA_datesHelper::uptime($time - $data->lastActivity);
        $data->lastActivityDate = $DATES->strDateTime($data->lastActivity);
    }
    // SuperUser can't be deleted.
    $data->delete = ($uid > 0);

    $login = $data;
}

$page->table->sortDatas();
$page->table->pagingDatas();
$page->table->getMinimumLines();

$page->table->sortColumn('status', 'S', true);
$page->table->sortColumn('uid', 'id', true);
$page->table->sortColumn('login', $TEXT['login']);
$page->table->sortColumn('email', $TEXT['email_addr']);
$page->table->sortColumn('lastActivity', $TEXT['last_activity']);
$page->table->sortColumn('hasComment', 'C', true);
$page->table->sortColumn('hasHash', 'H', true);

/*
* Setup search widget
*/
$PMA->widgets->newModule('search');

$search = new PMA_search();
$search->setCMDroute('murmur_registrations');
$search->setCMDname('registrations_search');
if (isset($_SESSION['search']['registrations'])) {
    $search->setSearchValue($_SESSION['search']['registrations']);
    $search->setTotalFound($page->table->searchFound);
    $search->setRemoveSearchHREF('?cmd=murmur_registrations&amp;reset_registrations_search');
}

$PMA->widgets->saveDatas('search', $search);

