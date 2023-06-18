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
    <a href="?addBan" class="button" title="<?= $TEXT['add_ban']; ?>">
        <img src="<?= PMA_IMG_ADD_22; ?>" alt="" />
    </a>
</div>

<?php require $PMA->widgets->getViewPath('tablePagingMenu'); ?>

<table id="murmurBans">

    <tr class="pad">
        <th>
        </th>
        <th class="icon">
        </th>
        <th class="large">
            <?= $TEXT['started'], PHP_EOL; ?>
        </th>
        <th class="large">
            <?= $TEXT['end'], PHP_EOL; ?>
        </th>
        <th class="icon"></th>
    </tr>

<?php foreach ($page->table->datas as $d): ?>
    <tr>
<?php if (is_object($d)): ?>
        <td class="selection large">
            <a href="?edit_ban_id=<?= $d->key; ?>">
<?php if ($d->userName !== ''): ?>
                <mark class="text"><?= htEnc($d->userName); ?></mark>
<?php endif; ?>
                <br />
                <?= $d->ip, PHP_EOL; ?>
<?php if ($d->mask !== ''): ?>
                / <mark class="mask"><?= $d->mask; ?></mark>
<?php endif; ?>
                <br />
<?php if ($d->reason !== ''): ?>
                <span class="text info"><?= htEnc(replaceEOL($d->reason)); ?></span>
<?php endif; ?>
            </a>
        </td>
        <td class="icon">
<?php if ($d->hash): ?>
            <img src="<?= PMA_IMG_OK_16; ?>" title="<?= $TEXT['cert_included']; ?>" alt="" />
<?php endif; ?>
        </td>
        <td>
            <?= $d->startedDate; ?><br />
            <?= $d->startedTime, PHP_EOL; ?>
        </td>
        <td>
            <?= $d->durationDate; ?><br />
            <?= $d->durationTime, PHP_EOL; ?>
        </td>
        <td class="icon">
            <a href="?delete_ban_id=<?= $d->key; ?>" class="button" title="<?= $TEXT['del_ban']; ?>" onClick="return popupDeleteBan(this, '<?= $d->key; ?>');">
                <img src="<?= PMA_IMG_TRASH_16; ?>" alt="" />
            </a>
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

<?php require $PMA->widgets->getViewPath('tablePagingMenu');
