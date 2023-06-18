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
</div>

<table>

    <tr class="pad">
        <th class="large">
            <a href="<?= $page->table->getColHref('ip'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('ip'); ?></a>
        </th>
        <th class="small">
            <a href="<?= $page->table->getColHref('start'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('start'); ?></a>
        </th>
        <th class="small">
            <a href="<?= $page->table->getColHref('end'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('end'); ?></a>
        </th>
        <th class="small">
            <a href="<?= $page->table->getColHref('comment'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('comment'); ?></a>
        </th>
        <th class="icon">
            Delete
        </th>
    </tr>

<?php foreach ($page->table->datas as $d): ?>
    <tr>
<?php if (is_object($d)): ?>
        <td>
            <?= $d->ip, PHP_EOL; ?>
        </td>
        <td>
            <?= $d->startStr, PHP_EOL; ?>
        </td>
        <td>
            <?= $d->endStr, PHP_EOL; ?>
        </td>
        <td>
            <?= $d->comment, PHP_EOL; ?>
        </td>
        <td class="icon">
<?php if ($d->delete): ?>
            <img src="<?= PMA_IMG_TRASH_16; ?>" class="button" alt="" />
<?php endif; ?>
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
<?php endif; ?>
    </tr>
<?php endforeach; ?>

</table>
