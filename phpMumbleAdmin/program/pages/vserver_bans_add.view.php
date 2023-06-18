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

<form method="post" onSubmit="return validateBanEditor(this);">

    <input type="hidden" name="cmd" value="murmur_bans" />
    <input type="hidden" name="addBan" />
    <input type="hidden" name="hash" />

    <table class="config">

        <tr>
            <th class="title"><?= $TEXT['add_ban']; ?></th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="ip"><?= $TEXT['ip_addr']; ?></label>
            </th>
            <td>
                <input type="text" autofocus="autofocus" required="required" id="ip" name="ip" />
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['submit']; ?>" />
            </th>
        </tr>

        <tr class="pad">
            <td class="hide" colspan="2"></td>
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
                <input type="text" id ="mask" name="mask" maxlength="3" class="medium" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="name"><?= $TEXT['login']; ?></label>
            </th>
            <td>
                <input type="text" id="name" name="name" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="reason"><?= $TEXT['reason']; ?></label>
            </th>
            <td>
                <textarea id="reason" name="reason" cols="4" rows="6"></textarea>
            </td>
        </tr>

        <tr>
            <th colspan="2">
<?php require $PMA->widgets->getViewPath('banDurationSelector'); ?>
            </th>
        </tr>

    </table>

</form>
