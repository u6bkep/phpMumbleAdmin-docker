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

$widget = $PMA->popups->getDatas('mumbleRegistrationEditor'); ?>

<form id="mumbleRegistrationEditor" method="POST" class="actionBox medium" onSubmit="return validateMumbleRegistrationEditor(this)">

    <input type="hidden" name="cmd" value="murmur_registrations" />
    <input type="hidden" name="editRegistration" />

    <h3>
<?php require PMA_DIR_POPUPS.'buttonCancel.inc'; ?>
        <img src="<?= PMA_IMG_EDIT_16; ?>" alt="" />
        <?= $TEXT['edit_account'], PHP_EOL; ?>
    </h3>

    <div class="body">

        <table class="config">

<?php if ($widget->is_set('login')): ?>
                <tr>
                    <th>
                        <label for="login"><?= $TEXT['login']; ?></label>
                    </th>
                    <td>
                        <input type="text" required="required" id="login" name="login" value="<?= $widget->get('login'); ?>" />
                    </td>
                </tr>
<?php endif; ?>

<?php if ($widget->is_set('pw')): ?>

            <tr class="pad">
                <td colspan="2" class="hide">
                </td>
            </tr>

<?php if ($widget->ownAccount): ?>
            <tr>
                <th>
                    <label for="current"><?= $TEXT['enter_your_pw']; ?></label>
                </th>
                <td>
                    <input type="password" id="current" name="current" value="" />
                </td>
            </tr>
<?php endif; ?>

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
<?php endif; ?>

            <tr class="pad">
                <td colspan="2" class="hide">
                </td>
            </tr>

            <tr>
                <th>
                    <label for="email"><?= $TEXT['email_addr']; ?></label>
<?php if ($widget->certificat !== ''): ?>
                        <span class="tooltip">
                            <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                            <span class="desc"><?= $TEXT['cert_email_info']; ?></span>
                        </span>
<?php endif; ?>
                </th>
                <td>
                    <input type="email" id="email" name="email" value="<?= $widget->get('email'); ?>" />
                </td>
            </tr>

            <tr>
                <th>
                    <label for="comm"><?= $TEXT['comment']; ?></label>
                </th>
                <td>
                    <textarea id="comm" name="comm" rows="10" cols="4"><?= $widget->get('description'); ?></textarea>
                </td>
            </tr>

        </table>

    </div>

    <div class="submit">
        <input type="submit" value="<?= $TEXT['modify']; ?>" />
    </div>

</form>
