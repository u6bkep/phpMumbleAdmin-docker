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
<?php require $PMA->widgets->getViewPath('route_subTabs'); ?>
</div>

<form method="post" onSubmit="return isFormModified(this);">

    <input type="hidden" name="cmd" value="config" />
    <input type="hidden" name="set_settings_autoban" />

    <table class="config">
        <tr class="pad">
            <th class="title"></th>
        </tr>

        <tr>
            <th>
                <label for="attempts"><?= $TEXT['autoban_attemps']; ?></label>
            </th>
            <td>
                <input type="text" class="small" id="attempts" name="attempts" value="<?= $PMA->config->get('autoban_attempts'); ?>" />
                <?= $TEXT['disable_function'], PHP_EOL; ?>
            </td>
        </tr>

        <tr>
            <th>
                <label for="timeFrame"><?= $TEXT['autoban_frame']; ?></label>
            </th>
            <td>
                <input type="text" id="timeFrame" name="timeFrame" value="<?= $PMA->config->get('autoban_frame'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="duration"><?= $TEXT['autoban_duration']; ?></label>
            </th>
            <td>
                <input type="text" id="duration" name="duration" value="<?= $PMA->config->get('autoban_duration'); ?>" />
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['apply']; ?>" />
            </th>
        </tr>

    </table>

</form>
