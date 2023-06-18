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
* Setup table.
*/
$bans = array();
foreach($PMA->bans->getAllDatas() as $ban) {

    $data = new stdClass();

    $data->ip = $ban['ip'];
    $data->start = $ban['start'];
    $data->startStr = $DATES->strDateTime($ban['start']);
    $data->end = $ban['start'] + $ban['duration'];
    $data->endStr = $DATES->strDateTime($data->end);
    $data->comment = $ban['comment'];
    $data->delete = false;

    $bans[] = $data;
}

$page->table = new PMA_table($bans);
$page->table->setNavigation($PMA->router->getTableNavigation('ip'));

$page->table->sortDatas();
$page->table->getMinimumLines();

$page->table->sortColumn('ip', $TEXT['ip_addr']);
$page->table->sortColumn('start', $TEXT['started']);
$page->table->sortColumn('end', $TEXT['end']);
$page->table->sortColumn('comment', $TEXT['comment']);

//$PMA->bans->add('127.0.0.1', 99999, 'TEST');
