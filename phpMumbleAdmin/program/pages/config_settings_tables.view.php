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
    <input type="hidden" name="set_settings_tables" />

    <table class="config">

        <tr class="pad">
            <th class="title"><?= $TEXT['tables']; ?></th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="overview"><?= $TEXT['overview_table_lines']; ?></label>
            </th>
            <td>
                <input type="text" class="small" maxlength="4" id="overview" name="overview" value="<?= $page->get('overview'); ?>" />
                <?= $TEXT['tables_infos'], PHP_EOL; ?>
            </td>
        </tr>

        <tr>
            <th>
                <label for="users"><?= $TEXT['users_table_lines']; ?></label>
            </th>
            <td>
                <input type="text" class="small" maxlength="4" id="users" name="users" value="<?= $page->get('users'); ?>" />
                <?= $TEXT['tables_infos'], PHP_EOL; ?>
            </td>
        </tr>

        <tr>
            <th>
                <label for="bans"><?= $TEXT['ban_table_lines']; ?></label>
            </th>
            <td>
                <input type="text" class="small" maxlength="4" id="bans" name="bans" value="<?= $page->get('bans'); ?>" />
                <?= $TEXT['tables_infos']; ?>
            </td>
        </tr>

        <tr class="pad">
            <td class="hide" colspan="2"></td>
        </tr>

        <tr class="pad">
            <th class="title"><?= $TEXT['overview_table']; ?></th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="totalOnline"><?= $TEXT['enable_connected_users']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('totalOnline'); ?> id="totalOnline" name="totalOnline" />
                <label for="totalOnlineSa"><?= $TEXT['sa_only']; ?></label>
                <input type="checkbox" <?= $page->chked('totalOnlineSa'); ?> id="totalOnlineSa" name="totalOnlineSa" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="uptime"><?= $TEXT['enable_vserver_uptime']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('uptime'); ?> id="uptime" name="uptime" />
                <label for="uptimeSa"><?= $TEXT['sa_only']; ?></label>
                <input type="checkbox" <?= $page->chked('uptimeSa'); ?> id="uptimeSa" name="uptimeSa" />
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['apply']; ?>" />
            </th>
        </tr>

    </table>

</form>
