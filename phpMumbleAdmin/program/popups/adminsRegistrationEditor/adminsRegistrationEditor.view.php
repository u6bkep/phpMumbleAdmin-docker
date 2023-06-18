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

<form id="adminsRegistrationEditor" method="POST" class="actionBox medium" onSubmit="return validateAdminEditor(this);">

    <input type="hidden" name="cmd" value="config_admins" />
    <input type="hidden" name="edit_registration" value="<?= $widget->get('id'); ?>" />

    <h3>
<?php require PMA_DIR_POPUPS.'buttonCancel.inc'; ?>
        <img src="<?= PMA_IMG_EDIT_16; ?>" alt="" />
        <?= $TEXT['edit_account'], PHP_EOL; ?>
    </h3>

    <div class="body">

        <table class="config">

            <tr>
                <th>
                    <label for="login"><?= $TEXT['login']; ?></label>
                </th>
                <td>
                    <input type="text" required="required" id="login" name="login" value="<?= $widget->get('login'); ?>" />
                </td>
            </tr>

            <tr class="pad">
                <td class="hide" colspan="2"></td>
            </tr>

            <tr>
                <th><?= $TEXT['class']; ?></th>
                <td>
                    <select name="class">
<?php foreach ($widget->classes as $o): ?>
                        <option value="<?= $o->class; ?>" <?= HTML::selected($o->selected); ?>><?= $o->className; ?></option>
<?php endforeach; ?>
                    </select>
                </td>
            </tr>

            <tr class="pad">
                <td class="hide" colspan="2"></td>
            </tr>

            <tr>
                <th>
                    <label for="new_pw"><?= $TEXT['new_pw']; ?></label>
                </th>
                <td>
                    <input type="password" id="new_pw" name="new_pw" value="" />
                </td>
            </tr>

            <tr>
                <th>
                    <label for="confirm_new_pw"><?= $TEXT['confirm_pw']; ?></label>
                </th>
                <td>
                    <input type="password" id="confirm_new_pw" name="confirm_new_pw" value="" />
                </td>
            </tr>

            <tr class="pad">
                <td class="hide" colspan="2"></td>
            </tr>

            <tr>
                <th>
                    <label for="email"><?= $TEXT['email_addr']; ?></label>
                </th>
                <td>
                    <input type="email" id="email" name="email" value="<?= $widget->get('email'); ?>" />
                </td>
            </tr>

            <tr>
                <th>
                    <label for="name"><?= $TEXT['user_name']; ?></label>
                </th>
                <td>
                    <input type="text" id="name" name="name" value="<?= $widget->get('name'); ?>" />
                </td>
            </tr>

            <tr>
                <th class="mid" colspan="2">
                    <input type="submit" value="<?= $TEXT['modify']; ?>" />
                </th>
            </tr>

        </table>

    </div>

</form>
