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

$PMA->popups->newHidden('bansDelete');
$PMA->widgets->newModule('tablePagingMenu');

$page->table = new PMA_table($getBans);

$page->table->setMaxPerPage($PMA->config->get('table_bans'));
$page->table->setNavigation($PMA->router->getTableNavigation('id'));

$page->table->pagingDatas();
$page->table->getMinimumLines();

foreach ($page->table->datas as $key => &$ban) {

    if (is_object($ban)) {

        $data = new stdClass();

        $ip = PMA_ipHelper::decimalTostring($ban->address);
        // mask 128 bits mean ip only, no need to show the mask

        $data->key = $key;
        $data->ip = $ip['ip'];
        $data->mask = '';
        if ($ban->bits !== 128) {
            if ($ip['type'] === 'ipv4') {
                $data->mask = PMA_ipHelper::mask6To4($ban->bits);
            } else {
                $data->mask = $ban->bits;
            }
        }
        $data->userName = $ban->name;
        $data->reason = $ban->reason;
        $data->hash = ($ban->hash !== '');
        $data->startedDate = $DATES->strDate($ban->start);
        $data->startedTime = $DATES->strTime($ban->start);

        $data->durationDate = '';
        $data->durationTime = '';
        if ($ban->duration > 0) {
            $ts = $ban->start + $ban->duration;
            $data->durationDate = $DATES->strDate($ts);
            $data->durationTime = $DATES->strTime($ts);
        }

        $ban = $data;
    }
}
