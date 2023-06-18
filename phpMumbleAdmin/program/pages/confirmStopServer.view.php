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

<form method="POST" class="actionBox alert small">

    <input type="hidden" name="cmd" value="overview" />
    <input type="hidden" name="toggle_server_status" value="<?= $_GET['confirmStopSrv']; ?>" />
    <input type="hidden" name="confirm_stop_sid" value="" />

    <h3>
        <a href="./" class="button right" title="<?= $TEXT['cancel']; ?>">
            <img src="<?= PMA_IMG_CANCEL_12; ?>" alt="" />
        </a>
        <?= $TEXT['server_not_empty'], PHP_EOL; ?>
    </h3>

    <div class="body">
        <p>
            <label for="kick"><?= $TEXT['kick_users']; ?></label>
            <input type="checkbox" id="kick" name="kickAllUsers" />
            <span class="tooltip">
                <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                <span class="desc"><?= $TEXT['kick_users_info']; ?></span>
            </span>
        </p>
    </div>

    <div class="body">
        <textarea placeholder="<?= $TEXT['stop_raison']; ?>" name="msg" rows="10" cols="4"></textarea>
    </div>

    <div class="submit">
        <input type="submit" name="confirmed" value="<?= $TEXT['stop_server']; ?>" />
    </div>

</form>
