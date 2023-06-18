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
* Setup popups
*/
$PMA->popups->newHidden('adminAdd');
$PMA->popups->newHidden('adminDelete');
/*
* Setup table.
*/
$admins = array();
foreach($PMA->admins->getAllDatas() as $admin) {
    if ($PMA->user->isSuperior($admin['class'])) {

        $data = new stdClass();

        $data->id = $admin['id'];
        $data->class = $admin['class'];
        $data->className = pmaGetClassName($data->class);
        $data->login = $admin['login'];
        $data->loginEnc = htEnc($admin['login']);
        $data->created = $admin['created'];
        $data->email = $admin['email'];
        $data->name = $admin['name'];
        $data->lastConn = '';
        $data->lastConnDate = '';
        if (is_int($admin['last_conn']) && $admin['last_conn'] > 0) {
            $data->lastConn = PMA_datesHelper::uptime(time() - $admin['last_conn']);
            $data->lastConnDate = $DATES->strDateTime($admin['last_conn']);
        }

        // Admin access datas
        $data->access = array();
        foreach ($admin['access'] as $id => $servers) {
            $name = $PMA->profiles->getName($id);
            if ($servers === '*') {
                $text = sprintf($TEXT['full_access'], $name);
            } else {
                $count = count(explode(';', $servers));
                $text = sprintf($TEXT['srv_access'], $name, $count);
            }
            $data->access[] = $text;
        }
        $admins[] = $data;
    }
}

$page->table = new PMA_table($admins);

$page->table->setNavigation($PMA->router->getTableNavigation('id'));

$page->table->sortDatas();
$page->table->getMinimumLines();

$page->table->sortColumn('class', $TEXT['class']);
$page->table->sortColumn('id', 'id', true);
$page->table->sortColumn('login', $TEXT['login']);
$page->table->sortColumn('lastConn', $TEXT['last_conn']);

