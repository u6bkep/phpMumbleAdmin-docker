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

if (count($page->table->pagingMenu) < 2) {
    return;
}
?>

<div class="tablePaging">

<?php if ($page->table->currentPage === 1): ?>
    <span class="nav first"><img src="<?= PMA_IMG_SPACE_16; ?>" alt="" /></span>
    <span class="nav prev"><img src="<?= PMA_IMG_SPACE_16; ?>" alt="" /></span>
<?php else: ?>
    <a class="nav first" title="<?= $TEXT['go_first']; ?>" href="?tablePage=1">
        <span><img src="images/tango/page_first_16.png" alt="" /></span>
    </a>
    <a class="nav prev" title="<?= $TEXT['go_prev']; ?>" href="?tablePage=<?= ($page->table->currentPage -1); ?>">
        <span><img src="images/tango/page_prev_16.png" alt="" /></span>
    </a>
<?php endif; ?>

<?php foreach ($page->table->pagingMenu as $m): ?>
    <a class="nav <?= HTML::selectedCss($m->selected); ?>" href="?tablePage=<?= $m->page; ?>">
        <?= $m->page, PHP_EOL; ?>
    </a>
<?php endforeach; ?>

<?php if ($page->table->currentPage === $page->table->totalOfPages): ?>
    <span class="nav next"><img src="<?= PMA_IMG_SPACE_16; ?>" alt="" /></span>
    <span class="nav last"><img src="<?= PMA_IMG_SPACE_16; ?>" alt="" /></span>
<?php else: ?>
    <a class="nav next" title="<?= $TEXT['go_next']; ?>" href="?tablePage=<?= ($page->table->currentPage +1); ?>">
        <span><img src="images/tango/page_next_16.png" alt="" /></span>
    </a>
    <a class="nav last" title="<?= $TEXT['go_last']; ?>" href="?tablePage=<?= $page->table->totalOfPages; ?>">
        <span><img src="images/tango/page_last_16.png" alt="" /></span>
    </a>
<?php endif; ?>

    <span class="nav total"><?= sprintf($TEXT['total_pages'], $page->table->totalOfPages); ?></span>

<?php if ($page->table->totalOfPages > 9): ?>
    <form method="GET" class="go">
        <input type="text" class="medium" name="tablePage" required="required" />
        <input type="submit" value="GO" />
    </form>
<?php endif; ?>

</div>
