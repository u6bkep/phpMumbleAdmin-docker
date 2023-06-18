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

if (! defined('PMA_STARTED')) { die('You cannot call this script directly !'); } ?>

<div class="toolbar">
    <img src="images/tango/clock_22.png" alt="" />
    <?= $TEXT['pw_request_pending'], PHP_EOL; ?>
</div>

<table>

    <tr class="pad">
        <th class="large">
            <a href="<?= $page->table->getColHref('end'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('end'); ?></a>
        </th>
        <th>
            <a href="<?= $page->table->getColHref('login'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('login'); ?></a>
        </th>
        <th class="large">
            <a href="<?= $page->table->getColHref('ip'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('ip'); ?></a>
        </th>
        <th class="id">
            <a href="<?= $page->table->getColHref('pid'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('pid'); ?></a>
        </th>
        <th class="id">
            sid
        </th>
        <th class="id">
            uid
        </th>
        <th>
            <?= $TEXT['request_id'], PHP_EOL; ?>
        </th>
    </tr>

<?php foreach ($page->table->datas as $d): ?>
    <tr>
<?php if (is_object($d)): ?>
        <td>
            <time class="tHelp" datetime="<?= $d->dateTime; ?>"  title="<?= sprintf($TEXT['started_at'], $d->date, $d->time); ?>"><?= $d->uptime; ?></time>
        </td>
        <td>
            <?= htEnc($d->login), PHP_EOL; ?>
        </td>
        <td>
            <?= $d->ip, PHP_EOL; ?>
        </td>
        <td class="icon">
            <?= $d->pid, PHP_EOL; ?>
        </td>
        <td class="icon">
            <?= $d->sid, PHP_EOL; ?>
        </td>
        <td class="icon">
            <?= $d->uid, PHP_EOL; ?>
        </td>
        <td>
            <?= $d->id, PHP_EOL; ?>
        </td>
<?php else: ?>
        <td>
        </td>
        <td>
        </td>
        <td>
        </td>
        <td>
        </td>
        <td>
        </td>
        <td>
        </td>
        <td>
        </td>
<?php endif; ?>
    </tr>
<?php endforeach; ?>

    <tr class="pad">
        <th colspan="7">
            <abbr class="tHelp" title="<?= $TEXT['ice_profile'];?>">pid</abbr> -
            <abbr class="tHelp" title="<?= $TEXT['sid'];?>">sid</abbr> -
            <abbr class="tHelp" title="<?= $TEXT['uid'];?>">uid</abbr>
        </th>
    </tr>

</table>
