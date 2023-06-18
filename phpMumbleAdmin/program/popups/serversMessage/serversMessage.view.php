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

<form id="serversMessage" method="POST" class="actionBox small">

    <input type="hidden" name="cmd" value="overview" />

    <h3>
<?php require PMA_DIR_POPUPS.'buttonCancel.inc'; ?>
        <img src="<?= PMA_IMG_MSG_16; ?>" alt="" />
        <label for="msg"><?= $TEXT['msg_all_srv']; ?></label>
    </h3>

    <fieldset>

        <div class="body">
            <textarea autofocus="autofocus" required="required" id="msg" name="messageToServers" rows="10" cols="4"></textarea>
        </div>

        <div class="submit">
            <input type="submit" value="<?= $TEXT['submit']; ?>" />
        </div>

    </fieldset>

</form>
