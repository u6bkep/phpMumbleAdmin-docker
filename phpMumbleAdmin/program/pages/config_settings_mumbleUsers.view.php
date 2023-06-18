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
    <input type="hidden" name="set_mumble_users" />

    <table class="config">

        <tr class="pad">
            <th class="title"></th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="offline"><?= $TEXT['allowOfflineAuth']; ?></label>
                <span class="tooltip">
                    <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                    <span class="desc"><?= $TEXT['allowOfflineAuth_info']; ?></span>
                </span>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('allowOfflineAuth'); ?> id="offline" name="allowOfflineAuth" />
            </td>
        </tr>

        <tr class="pad">
            <td class="hide" colspan="2"></td>
        </tr>

        <tr class="pad">
            <th class="title">SuperUsers</th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="suAuth"><?= $TEXT['activate_su_login']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('suAuth'); ?> id="suAuth" name="allowSuperUserAuth" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="suEditPw"><?= $TEXT['activate_su_modify_pw']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('suEditUserPw'); ?> id="suEditPw" name="allowSuperUserEditPw" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="suStart"><?= $TEXT['activate_su_vserver_start']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('suStartServer'); ?> id="suStart" name="allowSuperUserStartSrv" />
            </td>
        </tr>

        <tr class="pad">
            <td class="hide" colspan="2"></td>
        </tr>

        <tr class="pad">
            <th class="title">SuperUser_ru</th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="suRuClass"><?= $TEXT['activate_su_ru']; ?></label>
                <span class="tooltip">
                    <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                    <span class="desc"><?= $TEXT['activate_su_ru_info']; ?></span>
                </span>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('suRuActive'); ?> id="suRuClass" name="allowSuperUserRuClass" />
            </td>
        </tr>

        <tr class="pad">
            <td class="hide" colspan="2"></td>
        </tr>

        <tr class="pad">
            <th class="title"><?= $TEXT['reg_users']; ?></th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="ruAuth"><?= $TEXT['activate_ru_login']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('ruAuth'); ?> id="ruAuth" name="allowRuAuth" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="ruDelAcc"><?= $TEXT['activate_ru_del_account']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('ruDeleteAcc'); ?> id="ruDelAcc" name="allowRuDelAccount" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="ruLogin"><?= $TEXT['activate_ru_modify_login']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('ruEditLogin'); ?> id="ruLogin" name="allowRuModifyLogin" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="pwGen"><?= $TEXT['activate_pwgen']; ?></label>
            </th>
            <td>
                <div class="right"><a href="?pw_requests_options"><?= $TEXT['tab_options']; ?></a></div>
                <input type="checkbox" <?= $page->chked('pwGenActive'); ?> id="pwGen" name="pwGenActive" />
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['apply']; ?>" />
            </th>
        </tr>

    </table>

</form>
