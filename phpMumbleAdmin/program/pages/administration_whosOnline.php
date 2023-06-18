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
* Setup stats.
*/
$page->statTotal = 0;
$page->statAuth = 0;
$page->statUnauth = 0;
/*
* Setup whos table datas.
*/
$time = time();
$requests = array();
foreach($PMA->whosOnline->getAllDatas() as $user) {

    $data = new stdClass();

    $data->class = $user['class'];
    $data->className = $user['classname'];
    $data->login = $user['login'];
    $data->ip = $user['current_ip'];
    $data->proxyed = isset($user['proxy']);
    $data->proxy = '';
    if ($data->proxyed) {
        $data->proxy = $user['proxy'];
    }
    $data->pid = $user['profile_id'];
    $data->sid = $user['sid'];
    $data->uid = $user['uid'];
    $data->lastActivity = PMA_datesHelper::uptime($time - $user['last_activity']);
    /*
    * Stats :
    */
    ++$page->statTotal;
    if ($user['class'] === PMA_USER_UNAUTH) {
        ++$page->statUnauth;
    } else {
        ++$page->statAuth;
    }

    $requests[] = $data;
}
/*
* Setup table.
*/
$page->table = new PMA_table($requests);
$page->table->setNavigation($PMA->router->getTableNavigation('class'));

$page->table->sortDatas();
$page->table->getMinimumLines();

$page->table->sortColumn('class', $TEXT['class']);
$page->table->sortColumn('login', $TEXT['login']);
$page->table->sortColumn('ip', $TEXT['ip_addr']);
$page->table->sortColumn('pid', 'pid', true);
$page->table->sortColumn('lastActivity', $TEXT['last_activity']);
