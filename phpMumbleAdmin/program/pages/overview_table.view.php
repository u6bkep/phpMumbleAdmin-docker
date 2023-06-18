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
<?php if ($page->defaultSettingsButton): ?>
    <a href="?murmurDefaultConf" class="button" title="<?= $TEXT['default_settings']; ?>" onClick="return popup('murmurDefaultConf');">
        <img src="<?= PMA_IMG_INFO_22; ?>" alt="" />
    </a>
    <a href="?murmurMassSettings" class="button" title="<?= $TEXT['mass_settings']; ?>" onClick="return popup('murmurMassSettings');">
        <img src="images/tango/settings_22.png" alt="" />
    </a>
    <img src="<?= PMA_IMG_SPACE_16; ?>" alt="" />
<?php endif;
if ($page->addServerButton): ?>
    <a href="?addServer" class="button" title="<?= $TEXT['add_srv']; ?>" onClick="return popup('serverAdd');">
        <img src="<?= PMA_IMG_ADD_22; ?>" alt="" />
    </a>
<?php endif;
if ($page->sendMessageButton): ?>
    <a href="?messageToServers" class="button" title="<?= $TEXT['msg_all_srv']; ?>" onClick="return popup('serversMessage');">
        <img src="<?= PMA_IMG_MSG_22; ?>" alt="" />
    </a>
<?php endif; ?>
</div>

<?php require $PMA->widgets->getViewPath('tablePagingMenu'); ?>

<table id="overview">

    <tr class="pad">
        <th class="icon">
            <a href="<?= $page->table->getColHref('isBooted'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('isBooted'); ?></a>
        </th>
        <th class="id">
            <a href="<?= $page->table->getColHref('key'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('key'); ?></a>
        </th>
        <th>
            <?= $TEXT['srv_name'], PHP_EOL; ?>
        </th>
        <th class="icon">
        </th>
        <th class="icon">
        </th>
<?php if ($page->showOnlineUsers): ?>
        <th class="small">
        </th>
<?php endif; ?>
        <th class="icon">
        </th>
        <th class="icon">
        </th>
    </tr>

<?php foreach ($page->table->datas as $d): ?>
    <tr>
<?php if (is_object($d)): ?>
        <td class="icon">
            <a href="?cmd=overview&amp;toggle_server_status=<?= $d->id; ?>" class="button">
                <img src="<?= $d->isBooted ? PMA_IMG_ONLINE_16 : PMA_IMG_OFFLINE_16; ?>" alt="" />
            </a>
        </td>
        <td class="id <?= HTML::selectedCss($d->selected); ?>">
            <?= $d->id, PHP_EOL; ?>
        </td>
        <td class="selection large">
            <a href="?page=vserver&amp;sid=<?= $d->id; ?>">
                <p class="text serverName"><?= $d->serverNameEnc; ?></p>
                <span class="info"><?= $d->host.':'.$d->port; ?></span>
<?php if ($d->uptime !== ''): ?>
                <span class="info tHelp" title="<?= sprintf($TEXT['started_at'], $d->date, $d->time); ?>">
                    (<?= $d->uptime; ?>)
                </span>
<?php endif; ?>
            </a>
        </td>
        <td class="icon">
            <a href="?resetServer=<?= $d->id; ?>" class="button" onClick="return popupResetSrv(this, '<?= $d->id; ?>', '<?= $d->serverNameEnc; ?>');">
                <img src="<?= PMA_IMG_RESET_16; ?>" alt="" />
            </a>
        </td>
        <td class="icon">
<?php if ($d->connURL !== ''): ?>
            <a href="<?= $d->connURL; ?>" class="button">
                <img src="<?= PMA_IMG_CONN_16; ?>" alt="" />
            </a>
<?php endif; ?>
        </td>
<?php if ($page->showOnlineUsers): ?>
        <td>
<?php if ($d->gauge !== ''): ?>
            <strong class="gauge <?= $d->gauge; ?>"><?= $d->users; ?></strong> / <?= $d->max, PHP_EOL; ?>
<?php endif; ?>
        </td>
<?php endif; ?>
        <td class="icon">
            <a href="?cmd=overview&amp;toggle_web_access=<?= $d->id; ?>" class="button">
                <img src="<?= $d->webAccess ? PMA_IMG_UNLOCKED_16 : PMA_IMG_LOCKED_16; ?>" alt="" />
            </a>
        </td>
        <td class="icon">
<?php if ($page->userCanDelete): ?>
            <a href="?deleteServer=<?= $d->id; ?>" class="button" onClick="return popupDeleteSrv(this, '<?= $d->id; ?>', '<?= $d->serverNameEnc; ?>');">
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
<?php if ($page->showOnlineUsers): ?>
        <td>
        </td>
<?php endif; ?>
        <td>
        </td>
        <td>
        </td>
<?php endif; ?>
    </tr>
<?php endforeach; ?>

</table>

<?php require $PMA->widgets->getViewPath('tablePagingMenu');
