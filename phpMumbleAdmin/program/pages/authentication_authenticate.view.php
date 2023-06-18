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

<form method="post" class="actionBox auth">

    <input type="hidden" name="cmd" value="auth" />

    <fieldset <?= $page->disabled('noCookie'); ?>>

    <legend><?= $TEXT['Authentication']; ?></legend>

        <table class="config pad">

            <tr>
                <th>
                    <label for="login"><?= $TEXT['login']; ?></label>
                </th>
                <td>
                    <input type="text" autofocus="autofocus" required="required" id="login" name="login" value="" />
                </td>
            </tr>

            <tr>
                <th>
                    <label for="pw"><?= $TEXT['pw']; ?></label>
                </th>
                <td>
                    <input type="password" required="required" id="pw" name="password" value="" />
                </td>
            </tr>

<?php if ($page->allowMumbleUsersAuth): ?>
            <tr>
                <th>
                    <label for="server_id"><?= $TEXT['server']; ?></label>
                </th>
                <td>
<?php require 'authentication_serverField.view.inc'; ?>
                </td>
            </tr>
<?php endif; ?>

            <tr>
                <th colspan="2">
                    <input type="submit" value="<?= $TEXT['enter']; ?>" />
                </th>
            </tr>

        </table>

    </fieldset>

<?php if ($page->allowPasswordRequests): ?>
    <div class="passwordRequest">
        <a href="?password_request"><?= $TEXT['gen_pw']; ?></a>
        <img src="<?= PMA_IMG_PW_22; ?>" alt="" />
    </div>
<?php endif; ?>

</form>
