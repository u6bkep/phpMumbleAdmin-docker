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

<aside id="viewerBox">

    <div class="toolbar <?= $page->viewerState; ?>">

<?php if (! empty($page->actionMenu)): ?>

        <div class="expand">
            <?= $TEXT['action'], PHP_EOL; ?>
            <img src="<?= PMA_IMG_ARROW_DOWN; ?>" alt="" />
            <ul>
<?php foreach ($page->actionMenu as $a): ?>
                <li>
<?php if (! is_null($a->href)): ?>
                    <a href="<?= $a->href; ?>" <?= $a->js; ?>>
<?php endif; ?>
                        <img src="<?= $a->img; ?>" alt="" />
                        <?= $a->text, PHP_EOL; ?>
<?php if (! is_null($a->href)): ?>
                    </a>
<?php endif; ?>
                </li>
<?php endforeach; ?>
            </ul>
        </div>

<?php require $PMA->widgets->getViewPath('route_subTabs');
endif; ?>

    </div>

<?php require $PMA->widgets->getViewPath($viewerBoxWidget->id); ?>

</aside>

<?php require $PMA->widgets->getViewPath('viewer'); ?>

<div class="clear"></div>
