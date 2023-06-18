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

$dur['hour'] = null;
$dur['day'] = null;
$dur['month'] = null;
$dur['year'] = null;
if (isset($page->endTimeStamp)) {
    $dur['hour'] = (int) date('H', $page->endTimeStamp);
    $dur['day'] = (int) date('d', $page->endTimeStamp);
    $dur['month'] = (int) date('m', $page->endTimeStamp);
    $dur['year'] = (int) date('Y', $page->endTimeStamp);
}
if (! isset($page->permanent)) {
    $page->permanent = true;
}

$months[1] = $TEXT['january'];
$months[2] = $TEXT['feburary'];
$months[3] = $TEXT['march'];
$months[4] = $TEXT['april'];
$months[5] = $TEXT['may'];
$months[6] = $TEXT['june'];
$months[7] = $TEXT['july'];
$months[8] = $TEXT['august'];
$months[9] = $TEXT['september'];
$months[10] = $TEXT['october'];
$months[11] = $TEXT['november'];
$months[12] = $TEXT['december'];

?>

<div id="ban_duration">

    <div><?= $TEXT['end']; ?></div>

    <select id="hour" name="hour" onChange="uncheck('permanent');">
        <option><?= $TEXT['hour']; ?></option>
<?php for ($i = 0; $i <= 23; ++$i): ?>
        <option <?= HTML::selected($i === $dur['hour']); ?> value="<?= $i; ?>"><?= $i; ?> H</option>
<?php endfor; ?>
    </select>

    <select id="day" name="day" onChange="uncheck('permanent');">
        <option><?= $TEXT['day']; ?></option>
<?php for ($i = 1; $i <= 31; ++$i): ?>
        <option <?= HTML::selected($i === $dur['day']); ?> value="<?= $i; ?>"><?= $i; ?></option>
<?php endfor; ?>
    </select>

    <select id="month" name="month" onChange="uncheck('permanent');">
        <option><?= $TEXT['month']; ?></option>
<?php for ($i = 1; $i <= 12; ++$i): ?>
        <option <?= HTML::selected($i === $dur['month']); ?> value="<?= $i; ?>"><?= $months[$i]; ?></option>
<?php endfor; ?>
    </select>

    <select id="year" name="year" onChange="uncheck('permanent');">
        <option><?= $TEXT['year'] ?></option>
<?php for ($i = date('Y'); $i <= 2037; ++$i): ?>
        <option <?= HTML::selected($i === $dur['year']); ?> value="<?= $i; ?>"><?= $i; ?></option>
<?php endfor; ?>
    </select>

    <div>
        <label for="permanent"><?= $TEXT['permanent']; ?></label>
        <input type="checkbox" id="permanent" name="permanent" <?= HTML::chked($page->permanent); ?> onClick="banDurationHlper(this);" />
    </div>

</div>
