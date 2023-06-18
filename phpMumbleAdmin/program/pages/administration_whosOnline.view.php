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
    <img src="<?= PMA_IMG_WHOIS_22; ?>" alt="" />
    <?= $TEXT['whos_online'], PHP_EOL;?>
</div>

<table>

    <thead>
        <tr class="pad">
            <th class="small">
                <a href="<?= $page->table->getColHref('class'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('class'); ?></a>
            </th>
            <th>
                <a href="<?= $page->table->getColHref('login'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('login'); ?></a>
            </th>
            <th class="vlarge">
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
            <th class="large">
                <a href="<?= $page->table->getColHref('lastActivity'); ?>" title="<?= $TEXT['sort_by']; ?>"><?= $page->table->getColText('lastActivity'); ?></a>
            </th>
        </tr>
    </thead>

    <tbody>
<?php foreach ($page->table->datas as $d): ?>

        <tr>
<?php if (is_object($d)): ?>
            <td class="<?= $d->className; ?>">
                <?= $d->className, PHP_EOL; ?>
            </td>
            <td>
                <?= htEnc($d->login), PHP_EOL; ?>
            </td>
            <td>
<?php if ($d->proxyed): ?>
                <img src="images/xchat/red_12.png" class="tHelp" title="<?= $TEXT['proxyed'].' (first IP: '.$d->proxy.')'; ?>" alt="" />
<?php endif; ?>
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
                <?= $d->lastActivity, PHP_EOL; ?>
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
    </tbody>

    <tfoot>
        <tr class="pad">
            <th colspan="7">
                <abbr class="tHelp" title="<?= $TEXT['ice_profile'];?>">pid</abbr> -
                <abbr class="tHelp" title="<?= $TEXT['sid'];?>">sid</abbr> -
                <abbr class="tHelp" title="<?= $TEXT['uid'];?>">uid</abbr>
                <br />
                Stats : <?= sprintf(
                    $TEXT['sessions_infos'],
                    '<mark>'.$page->statTotal.'</mark>',
                    '<mark>'.$page->statAuth.'</mark>',
                    '<mark>'.$page->statUnauth.'</mark>'
                ), PHP_EOL; ?>
            </th>
        </tr>
    </tfoot>

</table>
