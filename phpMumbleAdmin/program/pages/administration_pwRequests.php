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

$pwRequests = new PMA_datas_pwRequests();
/*
* Setup requests table datas.
*/
$time = time();
$requests = array();
foreach($pwRequests->getAllDatas() as $req) {

    $data = new stdClass();

    $data->id = $req['id'];
    $data->ip = $req['ip'];
    $data->login = $req['login'];
    $data->pid = $req['profile_id'];
    $data->sid = $req['sid'];
    $data->uid = $req['uid'];
    $data->end = $req['end'];
    $data->uptime = PMA_datesHelper::uptime($req['end'] - $time);
    $data->date = $DATES->strDate($req['start']);
    $data->time = $DATES->strTime($req['start']);
    $data->dateTime = $DATES->strDateTime($req['start']);

    $requests[] = $data;
}
/*
* Setup table.
*/
$page->table = new PMA_table($requests);
$page->table->setNavigation($PMA->router->getTableNavigation('end'));

$page->table->sortDatas();
$page->table->getMinimumLines();

$page->table->sortColumn('end', $TEXT['end']);
$page->table->sortColumn('login', $TEXT['login']);
$page->table->sortColumn('ip', $TEXT['ip_addr']);
$page->table->sortColumn('pid', 'pid', true);
