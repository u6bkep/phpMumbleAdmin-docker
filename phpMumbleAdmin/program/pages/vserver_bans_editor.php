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

if (! isset($getBans[$_GET['edit_ban_id']])) {
    throw new PMA_pageException();
}

$BAN = $getBans[$_GET['edit_ban_id']];
$ip = PMA_ipHelper::decimalTostring($BAN->address);
if ($ip['type'] === 'ipv4') {
    $mask = PMA_ipHelper::mask6To4($BAN->bits);
} else {
    $mask = $BAN->bits;
}

$page->set('banID', (int)$_GET['edit_ban_id']);
$page->set('ip', $ip['ip']);
$page->set('mask', $mask);
$page->set('login', $BAN->name);
$page->set('reason', $BAN->reason);
if ($BAN->hash !== '') {
    $page->set('hash', $BAN->hash);
}
$page->set('start', $DATES->strDateTime($BAN->start));

if ($BAN->duration !== 0) {
    $page->set('end', $DATES->strDateTime($BAN->start + $BAN->duration));
    $page->endTimeStamp = ($BAN->start + $BAN->duration); // selectBanDuration widget
    $page->permanent = false;
} else {
    $page->permanent = true;
}
