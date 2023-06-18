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

<div class="toolbar">
<?php require $PMA->widgets->getViewPath('route_subTabs'); ?>
</div>

<form method="post" onSubmit="return isFormModified(this);">

    <input type="hidden" name="cmd" value="config" />
    <input type="hidden" name="set_settings_ext_viewer" />

    <table class="config">

        <tr class="pad">
            <th class="title"></th>
        </tr>

        <tr>
            <th>
                <label for="enable"><?= $TEXT['external_viewer_enable']; ?></label>
            </th>
            <td>
<?php if ($page->extViewerEnable): ?>
                <div class="right">
                    <a href="<?= $page->get('path'); ?>?ext_viewer&amp;profile=<?= $page->get('id'); ?>&amp;server=*">
                        <?= $TEXT['see_external_viewer'], PHP_EOL; ?>
                    </a>
                </div>
<?php endif; ?>
                <input type="checkbox" <?= $page->chked('enable'); ?> id="enable" name="enable" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="width"><?= $TEXT['external_viewer_width']; ?></label>
            </th>
            <td>
                <input type="text" id="width" name="width" value="<?= $page->get('width'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="height"><?= $TEXT['external_viewer_height']; ?></label>
            </th>
            <td>
                <input type="text" id="height" name="height" value="<?= $page->get('height'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="vertical"><?= $TEXT['external_viewer_vertical']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('vertical'); ?> id="vertical" name="vertical" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="scroll"><?= $TEXT['external_viewer_scroll']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('scroll'); ?> id="scroll" name="scroll" />
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['apply']; ?>" />
            </th>
        </tr>

    </table>

</form>
