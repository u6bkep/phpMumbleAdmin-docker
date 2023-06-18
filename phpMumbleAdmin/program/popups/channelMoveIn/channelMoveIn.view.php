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

$widget = $PMA->popups->getDatas('channelMoveIn'); ?>

<form id="channelMoveIn" method="POST" class="actionBox" onSubmit="return isFormModified(this);">

    <input type="hidden" name="cmd" value="murmur_channel" />
    <input type="hidden" name="move_users_into_the_channel" value="" />

    <h3>
<?php require PMA_DIR_POPUPS.'buttonCancel.inc'; ?>
        <img src="<?= PMA_IMG_GO_UP_16; ?>" alt="" />
        <?= $TEXT['move_user_in_chan'], PHP_EOL; ?>
    </h3>

    <div class="body">

        <p><?= $TEXT['select_user_to_move']; ?></p>

        <ul class="scroll">
<?php foreach ($widget->scroll as $u): ?>
            <li>
                <input type="checkbox" id="id<?= $u->session; ?>" name="<?= $u->session; ?>" />
                <label for="id<?= $u->session; ?>"><?= htEnc($u->name); ?></label>
            </li>
<?php endforeach; ?>
        </ul>

    </div>

    <div class="submit">
        <input type="submit" value="<?= $TEXT['move']; ?>" />
    </div>

</form>
