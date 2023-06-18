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

$widget = $PMA->popups->getDatas('serverReset'); ?>

<form id="serverReset" method="POST" class="actionBox alert small">

    <input type="hidden" name="cmd" value="overview" />
    <input type="hidden" name="serverReset" value="<?= $widget->sid; ?>" />

    <h3>
        <img src="<?= PMA_IMG_RESET_16; ?>" alt="" />
        <?= $widget->get('serverName'), PHP_EOL; ?>
    </h3>

    <div class="body">
        <p><?= $widget->get('confirmText'); ?></p>
        <p>
            <label for="new_su_pw"><?= $TEXT['generate_su_pw']; ?></label>
            <input type="checkbox" id="new_su_pw" name="new_su_pw" checked="checked" />
        </p>
    </div>

<?php require PMA_DIR_POPUPS.'buttonsConfirm.inc'; ?>

</form>
