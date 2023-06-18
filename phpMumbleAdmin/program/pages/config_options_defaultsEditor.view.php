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
    <div class="right">
        <a href="./" class="button" title="<?= $TEXT['cancel']; ?>">
            <img src="<?= PMA_IMG_CANCEL_22; ?>" alt="" />
        </a>
    </div>
</div>

<form method="post" onSubmit="return isFormModified(this);">

    <input type="hidden" name="cmd" value="config" />
    <input type="hidden" name="set_default_options" />

    <table class="config">

        <tr class="pad">
            <th class="title" ><?= $TEXT['default_options']; ?></th>
            <td></td>
        </tr>

        <tr>
            <th><?= $TEXT['default_lang']; ?></th>
            <td>
                <select name="lang">
<?php foreach ($page->langs as $a): ?>
                    <option <?= HTML::selected($a['select']); ?> value="<?= $a['dir']; ?>"><?= $a['name']; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['default_style']; ?></th>
            <td>
                <select name="skin">
<?php foreach ($page->skins as $a): ?>
                    <option <?= HTML::selected($a['select']); ?> value="<?= $a['file']; ?>"><?= $a['name']; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['default_time']; ?></th>
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
            <th><?= $TEXT['default_time_format']; ?></th>
            <td>
                <select name="time">
<?php foreach ($page->timeFormats as $a): ?>
                <option <?= HTML::selected($a['select']); ?> value="<?= $a['option']; ?>"><?= $a['desc']; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['default_date_format']; ?></th>
            <td>
                <select name="date">
<?php foreach ($page->dateFormats as $a): ?>
                <option <?= HTML::selected($a['select']); ?> value="<?= $a['option']; ?>"><?= $a['desc']; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['default_locales']; ?></th>
            <td>
                <select name="systemLocales">
                    <option value=""><?= $TEXT['default']; ?></option>
<?php foreach ($page->systemLocales as $a): ?>
                    <option <?= HTML::selected($a['select']); ?> value="<?= $a['locale']; ?>"><?= $a['desc']; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['apply']; ?>" />
            </th>
        </tr>

    </table>
</form>

<form method="post">

    <input type="hidden" name="cmd" value="config" />
    <input type="hidden" name="add_locales_profile" />

    <table class="config">

        <tr class="pad">
            <th class="title"><?= $TEXT['add_locales_profile']; ?></th>
            <td></td>
        </tr>

        <tr>
            <th>Select a system locales</th>
            <td>
                <select name="key" required="required">
                    <option value=""><?= $TEXT['none']; ?></option>
<?php foreach ($page->availableSystemLocales as $locale): ?>
                    <option value="<?= $locale; ?>"><?= $locale; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th>Name of the profile</th>
            <td>
                <input type="text" required="required" name="val" />
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['add']; ?>" />
            </th>
        </tr>

    </table>

</form>

<form method="post">

    <input type="hidden" name="cmd" value="config" />

    <table class="config">

        <tr class="pad">
            <th class="title"><?= $TEXT['del_locales_profile']; ?></th>
            <td></td>
        </tr>

        <tr>
            <th></th>
            <td>
                <select name="delete_locales_profile" required="required">
                    <option value=""><?= $TEXT['none']; ?></option>
<?php foreach ($page->systemLocalesProfiles as $key => $value): ?>
                    <option value="<?= $key; ?>"><?= $value.' ('.$key.')'; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['delete']; ?>" />
            </th>
        </tr>

    </table>

</form>
