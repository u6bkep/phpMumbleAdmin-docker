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

if (! defined('PMA_STARTED')) { die('You cannot call this script directly !'); }

$widget = $PMA->popups->getDatas('murmurMassSettings'); ?>

<form method="POST" id="murmurMassSettings" class="actionBox medium">

    <input type="hidden" name="cmd" value="overview" />
    <input type="hidden" name="mass_settings" />
    <input type="hidden" name="confirm_word" value="<?= $TEXT['confirm_word']; ?>" />

    <h3>
<?php require PMA_DIR_POPUPS.'buttonCancel.inc'; ?>
        <img src="images/tango/settings_16.png" alt="" />
        <?= $TEXT['mass_settings'], PHP_EOL; ?>
    </h3>

    <table class="config">

        <tr>
            <th>
                <select name="key" required="required">
                    <option value=""><?= $TEXT['select_setting']; ?></option>
<?php foreach ($widget->settings as $key => $array): ?>
                    <option value="<?= $key; ?>"><?= $array['name']; ?></option>
<?php endforeach; ?>
                </select>
            </th>
            <td>
                <textarea name="value" rows="6" cols="6"></textarea>
            </td>
        </tr>

        <tr>
            <th>
               <?= sprintf($TEXT['confirm_with_word'], $TEXT['confirm_word']), PHP_EOL; ?>
            </th>
            <td>
                <input type="text" required="required" pattern="<?= $TEXT['confirm_word']; ?>" name="confirm" />
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" />
            </th>
        </tr>

    </table>

</form>
