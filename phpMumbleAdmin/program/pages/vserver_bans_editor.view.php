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
    <div class="right">
        <a href="./" class="button" title="<?= $TEXT['cancel']; ?>">
            <img src="<?= PMA_IMG_CANCEL_22; ?>" alt="" />
        </a>
    </div>
</div>

<form method="post" id="setBan" onSubmit="return validateBanEditor(this);">

    <input type="hidden" name="cmd" value="murmur_bans" />
    <input type="hidden" name="edit_ban_id" value="<?= $page->get('banID'); ?>" />

    <table class="config">

        <tr>
            <th class="title"><?= $TEXT['edit_ban']; ?></th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="ip"><?= $TEXT['ip_addr']; ?></label>
            </th>
            <td>
                <input type="text" required="required" id="ip" name="ip" value="<?= $page->get('ip'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="mask">
                    <?= $TEXT['bitmask'], PHP_EOL; ?>
                    <span class="tooltip">
                        <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                        <span class="desc"><?= $TEXT['bitmask_info']; ?></span>
                    </span>
                </label>
            </th>
            <td>
                <input type="text" id="mask" name="mask" maxlength="3" class="medium" value="<?= $page->get('mask'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="name"><?= $TEXT['login']; ?></label>
            </th>
            <td>
                <input type="text" id="name" name="name" value="<?= $page->get('login'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="reason"><?= $TEXT['reason']; ?></label>
            </th>
            <td>
                <textarea id="reason" name="reason" cols="4" rows="6"><?= $page->get('reason'); ?></textarea>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['cert_hash']; ?></th>
            <td>
<?php if ($page->is_set('hash')): ?>
                <a href="?cmd=murmur_bans&amp;remove_ban_hash=<?= $page->get('banID'); ?>" class="button right"
                    title="Remove ban certificate">
                    <img src="<?= PMA_IMG_TRASH_16; ?>" alt="" />
                </a>
                <?= $page->get('hash'), PHP_EOL; ?>
<?php else: ?>
                <?= $TEXT['none'], PHP_EOL; ?>
<?php endif; ?>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['started']; ?></th>
            <td><?= $page->get('start'); ?></td>
        </tr>

        <tr>
            <th><?= $TEXT['end']; ?></th>
<?php if ($page->is_set('end')): ?>
            <td><?= $page->get('end'); ?></td>
<?php else: ?>
            <td><?= $TEXT['permanent']; ?></td>
<?php endif; ?>
        </tr>

        <tr>
            <th colspan="2">
<?php require $PMA->widgets->getViewPath('banDurationSelector'); ?>
            </th>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['submit']; ?>" />
            </th>
        </tr>

    </table>
</form>
