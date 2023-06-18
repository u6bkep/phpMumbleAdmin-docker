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

$widget = $PMA->popups->getDatas('mumbleRegistrationDelete'); ?>

<form id="mumbleRegistrationDelete" method="POST" class="actionBox alert small">

    <input type="hidden" name="cmd" value="murmur_registrations" />
    <input type="hidden" name="delete_account" value="" />

    <h3>
        <img src="<?= PMA_IMG_TRASH_16; ?>" alt="" />
        <?= $widget->get('name'), PHP_EOL; ?>
    </h3>

    <div class="body">
        <p><?= $TEXT['confirm_delete_acc']; ?></p>
    </div>

<?php require PMA_DIR_POPUPS.'buttonsConfirm.inc'; ?>

</form>
