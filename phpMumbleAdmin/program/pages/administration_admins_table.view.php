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
    <a href="?add_admin" class="button" title="<?= $TEXT['add_admin']; ?>" onClick="return popup('adminAdd')">
        <img src="<?= PMA_IMG_ADD_22; ?>" alt="" />
    </a>
</div>

<table>

    <tr class="pad">
        <th class="small">
            <a href="<?= $page->table->getColHref('class'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('class'); ?></a>
        </th>
        <th class="id">
            <a href="<?= $page->table->getColHref('id'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('id'); ?></a>
        </th>
        <th>
            <a href="<?= $page->table->getColHref('login'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('login'); ?></a>
        </th>
        <th class="icon">
            A
        </th>
        <th class="large">
            <a href="<?= $page->table->getColHref('lastConn'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('lastConn'); ?></a>
        </th>
        <th class="icon">
        </th>
    </tr>

<?php foreach ($page->table->datas as $d): ?>
    <tr>
<?php if (is_object($d)): ?>
        <td class="<?= $d->className; ?>">
            <?= $d->className, PHP_EOL; ?>
        </td>
        <td class="id">
            <?= $d->id, PHP_EOL; ?>
        </td>
        <td  class="selection">
            <a href="?adminRegistration=<?= $d->id; ?>"><?= $d->loginEnc; ?></a>
        </td>
        <td class="icon tooltip">
<?php if (! empty($d->access)): ?>
            <span class="tooltip right">
                <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                <span class="desc">
<?php foreach ($d->access as $profile): ?>
                    <?= htEnc($profile); ?><br />
<?php endforeach; ?>
                </span>
            </span>
<?php endif; ?>
        </td>
        <td>
<?php if ($d->lastConn !== ''): ?>
            <span class="tHelp" title="<?= $d->lastConnDate; ?>"><?= $d->lastConn; ?></span>
<?php endif; ?>
        </td>
        <td class="icon">
            <a href="?remove_admin=<?= $d->id; ?>" class="button" title="<?= $TEXT['del_admin']; ?>" onClick="return popupDeleteAdmin(this, '<?= $d->id; ?>', '<?= $d->loginEnc; ?>')">
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
        <td>
        </td>
<?php endif; ?>
    </tr>
<?php endforeach; ?>

</table>

