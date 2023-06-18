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

<div id="vserverSettingsMore">

    <div class="toolbar">
<?php if ($page->resetCert): ?>
            <a href="?resetCertificate" class="button right" title="<?= sprintf($TEXT['reset_param'], 'certificate'); ?>"
                onClick="return popup('settingsResetCertificate');">
                <img src="<?= PMA_IMG_RESET_16; ?>" alt="" />
            </a>
<?php endif;
require $PMA->widgets->getViewPath('route_subTabs'); ?>
    </div>

<?php require $PMA->widgets->getViewPath('certificate'); ?>

    <form method="post" class="actionBox" enctype="multipart/form-data">

        <input type="hidden" name="cmd" value="murmur_settings" />
        <input type="hidden" name="MAX_FILE_SIZE" value="<?= MAX_FILE_SIZE_CERT ?>" />

            <h3><?= $TEXT['add_certificate']; ?></h3>

        <div class="body">
<?php if ($page->fileUpload): ?>
            <input type="file" required="required" name="add_certificate" />
<?php else: ?>
            <textarea required="required" name="add_certificate" rows="10" cols="4"></textarea>
<?php endif; ?>
        </div>

        <div class="submit">
            <input type="submit" value="<?= $TEXT['submit']; ?>" />
        </div>

    </form>

</div>
