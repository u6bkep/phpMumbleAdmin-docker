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

$widget = $PMA->widgets->getDatas('search'); ?>

    <form id="search" method="post" onSubmit="return unchanged(this.<?= $widget->CMDname; ?>);">

        <input type="hidden" name="cmd" value="<?= $widget->CMDroute; ?>" />

<?php if (! is_null($widget->searchValue)): ?>
        <?= $TEXT['found']; ?> : <span class="found"><?= $widget->totalFound; ?></span>
        <a href="<?= $widget->removeSearchHREF; ?>" class="button" title="<?= $TEXT['clean_search']; ?>">
            <img src="<?= PMA_IMG_CANCEL_22; ?>" alt="" />
        </a>
<?php endif; ?>

        <input type="text" name="<?= $widget->CMDname; ?>" value="<?= $widget->searchValue; ?>" />
        <input type="submit" value="<?= $TEXT['search']; ?>" />
    </form>
