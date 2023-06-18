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
<?php require $PMA->widgets->getViewPath('route_subTabs'); ?>
    </div>

    <iframe src="<?= PMA_FILE_SANDBOX_RELATIVE; ?>" sandbox="">
        <p>Your browser does not support iframes.</p>
    </iframe>

    <form method="post" class="actionBox" onSubmit="return unchanged(this.welcometext);">

        <input type="hidden" name="cmd" value="murmur_settings" />
        <input type="hidden" name="setConf" />

        <div class="body">
            <textarea name="welcometext" rows="10" cols="4"><?= htEnc($page->welcomeText); ?></textarea>
        </div>

        <div class="submit">
            <input type="submit" value="<?= $TEXT['apply']; ?>" />
        </div>

    </form>

</div>
