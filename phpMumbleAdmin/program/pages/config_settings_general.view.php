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
    <input type="hidden" name="set_settings_general" />

    <table class="config">

        <tr class="pad">
            <th class="title"></th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="title"><?= $TEXT['site_title']; ?></label>
            </th>
            <td>
                <input type="text" id="title" name="title" value="<?= $page->get('siteTitle'); ?>" />
            </td>

        <tr>
            <th>
                <label for="comment"><?= $TEXT['site_desc']; ?></label>
            </th>
            <td>
                <input type="text" id="comment" name="comment" value="<?= $page->get('siteComment'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="auto_logout"><?= $TEXT['autologout']; ?></label>
            </th>
            <td>
                <input type="number" class="medium" min="5" max="30" id="auto_logout" name="auto_logout" value="<?= $page->get('logout'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="update">
                    <?= $TEXT['autocheck_update'], PHP_EOL; ?>
                    <span class="tooltip">
                        <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                        <span class="desc"><?= $TEXT['autocheck_update_info']; ?></span>
                    </span>
                </label>
            </th>
            <td>
<?php if ($page->debug > 0): ?>
                <div class="right">
                    <a href="?cmd=config&amp;check_for_update=debug">debug</a>
                </div>
<?php endif; ?>
                <input type="number" class="medium" min="0" max="31" id="update" name="check_update" value="<?= $page->get('updateCheck'); ?>" />
                <a href="?cmd=config&amp;check_for_update"><?= $TEXT['check_update']; ?></a>
            </td>
        </tr>

        <tr>
            <th>
                <label for="mversion"><?= $TEXT['inc_murmur_vers']; ?></label>
                <span class="tooltip">
                    <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                    <span class="desc"><?= $TEXT['inc_murmur_vers_info']; ?></span>
                </span>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('murmurVersion'); ?> id="mversion" name="murmurVersionUrl" />
            </td>
        </tr>

        <tr class="pad">
            <td class="hide" colspan="2"></td>
        </tr>

        <tr class="pad">
            <th class="title"><?= $TEXT['srv_dropdown_list']; ?></th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="ddlAuth"><?= $TEXT['activate_auth_dropdown']; ?></label>
                <span class="tooltip">
                    <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                    <span class="desc"><?= $TEXT['activate_auth_dropdown_info']; ?></span>
                </span>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('ddlAuthPage'); ?> id="ddlAuth" name="ddlAuthPage" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="ddlRefresh"><?= $TEXT['refresh_ddl_cache']; ?></label>
            </th>
            <td>
                <input type="number" class="medium" min="0" id="ddlRefresh" name="ddlRefresh" value="<?= $page->get('ddlRefresh'); ?>" />
                <?= $TEXT['disable_function'], PHP_EOL; ?>
            </td>
        </tr>

        <tr>
            <th>
                <label for="show_uptime"><?= $TEXT['ddl_show_cache_uptime']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('ddlShowCacheUptime'); ?> id="show_uptime" name="show_uptime" />
            </td>
        </tr>

        <tr class="pad">
            <td class="hide" colspan="2"></td>
        </tr>

        <tr class="pad">
            <th class="title"></th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="showAvatar"><?= $TEXT['show_avatar']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('showAvatarSa'); ?> id="showAvatar" name="show_avatar_sa" />
            </td>
        </tr>

<?php if ($page->is_set('IcePhpIncludePath')): ?>
        <tr>
            <th>
                <label for="incPath"><?= $TEXT['IcePhp_include_path']; ?></label>
            </th>
            <td>
                <input type="text" id="incPath" name="incPath" value="<?= $page->get('IcePhpIncludePath'); ?>" />
            </td>
        </tr>
<?php endif; ?>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['apply']; ?>" />
            </th>
        </tr>

    </table>

</form>

<br />

<div class="information">
    <p>
        <strong>PHP include_path =</strong>
        <cite><?= $page->get('IcePhpIncludePathInfos'); ?></cite>
    </p>
</div>
