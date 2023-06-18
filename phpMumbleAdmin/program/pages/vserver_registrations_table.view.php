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
    <a href="?addMumbleAccount" class="button" title="<?= $TEXT['add_acc']; ?>" onClick="return popup('mumbleRegistrationAdd');">
        <img src="<?= PMA_IMG_ADD_22; ?>" alt="" />
    </a>
<?php require $PMA->widgets->getViewPath('search'); ?>
</div>

<?php require $PMA->widgets->getViewPath('tablePagingMenu'); ?>

<table>

    <tr class="pad">
        <th class="icon">
            <a href="<?= $page->table->getColHref('status'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('status'); ?></a>
        </th>
        <th class="id">
            <a href="<?= $page->table->getColHref('uid'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('uid'); ?></a>
        </th>
        <th>
            <a href="<?= $page->table->getColHref('login'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('login'); ?></a>
        </th>
        <th class="vlarge">
            <a href="<?= $page->table->getColHref('email'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('email'); ?></a>
        </th>
        <th class="large">
            <a href="<?= $page->table->getColHref('lastActivity'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('lastActivity'); ?></a>
        </th>
        <th class="icon">
            <a href="<?= $page->table->getColHref('hasComment'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('hasComment'); ?></a>
        </th>
        <th class="icon">
            <a href="<?= $page->table->getColHref('hasHash'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('hasHash'); ?></a>
        </th>
        <th class="icon">
        </th>
    </tr>

<?php foreach ($page->table->datas as $d): ?>
    <tr>
<?php if (is_object($d)): ?>
        <td class="icon">
<?php if ($d->status): ?>
            <a href="?tab=channels&amp;userSession=<?= $d->statusURL; ?>" class="button">
                <img src="<?= PMA_IMG_ONLINE_16; ?>" alt="" />
            </a>
<?php else: ?>
            <img src="<?= PMA_IMG_OFFLINE_16; ?>" class="button" alt="" />
<?php endif; ?>
        </td>
        <td class="id">
            <?= $d->uid, PHP_EOL; ?>
        </td>
        <td class="selection">
            <a href="?mumbleRegistration=<?= $d->uid; ?>">
                <span class="text"><?= $d->loginEnc; ?></span>
<?php if ($d->isRenamedSuperUser): ?>
                <i>(SuperUser)</i>
<?php endif; ?>
            </a>
        </td>
        <td>
<?php if ($d->email !== ''): ?>
            <a href="mailto:<?= $d->emailEnc; ?>" class="mailto" title="mailto:<?= $d->emailEnc; ?>">
                <?= $d->emailEnc, PHP_EOL; ?>
            </a>
<?php endif; ?>
        </td>
        <td>
<?php if ($d->lastActivityUptime !== ''): ?>
            <span class="tHelp" title="<?= $d->lastActivityDate; ?>"><?= $d->lastActivityUptime; ?></span>
<?php endif; ?>
        </td>
        <td class="icon">
<?php if ($d->hasComment): ?>
            <img src="<?= PMA_IMG_MUMBLE_COMMENT; ?>" alt="" />
<?php endif; ?>
        </td>
        <td class="icon">
<?php if ($d->hasHash): ?>
            <img src="<?= PMA_IMG_OK_16; ?>" alt="" />
<?php endif; ?>
        </td>
        <td class="icon">
<?php if ($d->delete): ?>
            <a href="?deleteMumbleAccountID=<?= $d->uid; ?>" class="button" onClick="return popupDeleteMumbleID(this, '<?= $d->uid; ?>', '<?= $d->loginEnc; ?>');">
                <img src="<?= PMA_IMG_TRASH_16; ?>" alt="" />
            </a>
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

