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

if (! isset($getTree) OR ! is_object($getTree)) {
    return;
}

$viewer->treeToArray($getTree); ?>

<div class="viewer <?= $viewer->css; ?>">
<?php foreach ($viewer->getDatas() as $d): ?>
    <p class="<?= $d->css; ?>">
<?php if (! is_null($d->href)): ?>
        <a href="<?= $d->href; ?>">
<?php endif; ?>
            <span class="deepIcons">
<?php foreach ($d->deepIcons as $src): ?>
                <img src="<?= $src; ?>" alt="" />
<?php endforeach; ?>
            </span>
<?php if (! empty($d->statusIcons)): ?>
            <span class="statusIcons">
<?php foreach ($d->statusIcons as $src): ?>
                <img src="<?= $src; ?>" alt="" />
<?php endforeach; ?>
            </span>
<?php endif; ?>
            <span class="<?= $d->textCss; ?>"><?= $d->nameEnc; ?></span>
<?php if (! is_null($d->href)): ?>
        </a>
<?php endif; ?>
    </p>
<?php endforeach; ?>
</div>
