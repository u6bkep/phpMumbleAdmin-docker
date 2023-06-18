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
<?php if ($page->editDefaultOptions): ?>
    <a href="?set_default_options" class="right"><?= $TEXT['default_options']; ?></a>
<?php endif;
if ($page->editSuperAdmin): ?>
    <a href="?edit_SuperAdmin" class="button" title="<?= $TEXT['change_your_pw']; ?>" onClick="return popup('optionsSuperAdminEditor');">
        <img src="<?= PMA_IMG_PW_22; ?>" alt="" />
    </a>
<?php elseif ($page->editAdminPassword): ?>
    <a href="?change_your_password" class="button" title="<?= $TEXT['change_your_pw']; ?>" onClick="return popup('optionsPasswordEditor');">
        <img src="<?= PMA_IMG_PW_22; ?>" alt="" />
    </a>
<?php endif; ?>
</div>

<form method="post" onSubmit="return isFormModified(this);">

    <input type="hidden" name="cmd" value="config" />
    <input type="hidden" name="set_options" />

    <table class="config">

        <tr>
            <th><?= $TEXT['select_lang']; ?></th>
            <td>
                <select name="lang">
<?php foreach ($page->langs as $a): ?>
                    <option <?= HTML::selected($a['select']); ?> value="<?= $a['dir']; ?>"><?= $a['name']; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['select_style']; ?></th>
            <td>
                <select name="skin">
<?php foreach ($page->skins as $a): ?>
                    <option <?= HTML::selected($a['select']); ?> value="<?= $a['file']; ?>"><?= $a['name']; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['select_time']; ?></th>
            <td>
                <select name="timezone">
<?php foreach ($page->timezones as $zones): ?>
                    <option disabled="disabled">---</option>
<?php foreach ($zones as $z): ?>
                    <option <?= HTML::selected($z->select); ?> value="<?= $z->tz; ?>"><?= $z->city; ?></option>
<?php endforeach;
endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['time_format']; ?></th>
            <td>
                <select name="time">
<?php foreach ($page->timeFormats as $a): ?>
                <option <?= HTML::selected($a['select']); ?> value="<?= $a['option']; ?>"><?= $a['desc']; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['date_format']; ?></th>
            <td>
                <select name="date">
<?php foreach ($page->dateFormats as $a): ?>
                <option <?= HTML::selected($a['select']); ?> value="<?= $a['option']; ?>"><?= $a['desc']; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['select_locales_profile']; ?></th>
            <td>
                <select name="locales">
                    <option value=""><?= $TEXT['none']; ?></option>
<?php foreach ($page->systemLocalesProfiles as $o): ?>
            <option <?= HTML::selected($o->select); ?> value="<?= $o->key; ?>"><?= $o->val; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['uptime_format']; ?></th>
            <td>
                <select name="uptime">
<?php foreach ($page->uptimeOptions as $o): ?>
            <option <?= HTML::selected($o->select); ?> value="<?= $o->val; ?>"><?= $o->uptime; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr class="pad">
            <td class="hide" colspan="2"></td>
        </tr>

        <tr>
            <th>
                <label for="vserver_login">
                    <?= $TEXT['conn_login'], PHP_EOL; ?>
                    <span class="tooltip">
                        <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                        <span class="desc"><?= $TEXT['conn_login_info']; ?></span>
                    </span>
                </label>
            </th>
            <td>
                <input type="text" id="vserver_login" name="vserver_login" value="<?= $page->get('vserversLogin'); ?>" />
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['apply']; ?>" />
            </th>
        </tr>

    </table>
</form>
