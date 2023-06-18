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
    <a href="?add_profile" class="button" title="<?= $TEXT['add_ICE_profile']; ?>" onClick="return popup('profileAdd');">
        <img src="<?= PMA_IMG_ADD_22; ?>" alt="" />
    </a>
<?php if ($page->addDefaultButton): ?>
    <a href="?cmd=config_ICE&amp;set_default_profile" class="button" title="<?= $TEXT['default_ICE_profile']; ?>">
        <img src="images/tango/fav_22.png" alt="" />
    </a>
<?php endif; ?>
</div>

<form method="post" onSubmit="return isFormModified(this);">

    <input type="hidden" name="cmd" value="config_ICE" />
    <input type="hidden" name="edit_profile" />

    <table class="config">

        <tr class="pad">
            <th class="title"></th>
            <td class="hide"></td>
        </tr>

        <tr>
            <th>
                <label for="name"><?= $TEXT['profile_name']; ?></label>
            </th>
            <td>
<?php if ($page->addDeleteProfileButton): ?>
                <a href="?delete_profile" class="button right" title="<?= $TEXT['del_profile']; ?>" onClick="return popup('profileDelete');">
                    <img src="<?= PMA_IMG_TRASH_16; ?>" alt="" />
                </a>
<?php endif; ?>
            <input type="text" required="required" id="name" name="name" value="<?= $page->get('profileName'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="public"><?= $TEXT['public_profile']; ?></label>
            </th>
            <td>
                <input type="checkbox" <?= $page->chked('isPublic'); ?> id="public" name="public" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="host"><?= $TEXT['ICE_host']; ?></label>
            </th>
            <td>
                <input type="text" required="required" id="host" name="host" value="<?= $page->get('host'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="port"><?= $TEXT['ICE_port']; ?></label>
            </th>
            <td>
                <input type="number" class="medium" min="0" max="65535" id="port" name="port" value="<?= $page->get('port'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="timeout"><?= $TEXT['ICE_timeout']; ?></label>
            </th>
            <td>
                <input type="number" class="medium" min="1" max="99" id="timeout" name="timeout" value="<?= $page->get('timeout'); ?>" />
            </td>
        </tr>

        <tr>
            <th>
                <label for="secret"><?= $TEXT['ICE_secret']; ?></label>
            </th>
            <td>
                <input type="text" id="secret" name="secret" value="<?= $page->get('secret'); ?>" />
            </td>
        </tr>

        <tr>
            <th><?= $TEXT['slice_php_file']; ?></th>
            <td>
                <select name="slice_php">
                    <option value=""><?= $TEXT['none']; ?></option>
<?php foreach ($page->slicesPhpProfiles as $o): ?>
                    <option <?= HTML::selected($o->select); ?> value="<?= $o->filename; ?>"><?= $o->name; ?></option>
<?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <th>
                <label for="http_addr">
                    <?= $TEXT['conn_url'], PHP_EOL; ?>
                    <span class="tooltip">
                        <img src="<?= PMA_IMG_INFO_16; ?>" alt="" />
                        <span class="desc"><?= $TEXT['conn_url_info']; ?></span>
                    </span>
                </label>
            </th>
            <td>
                <input type="text" id="http_addr" name="http_addr" value="<?= $page->get('httpAddr'); ?>" />
            </td>
        </tr>

        <tr>
            <th colspan="2">
                <input type="submit" value="<?= $TEXT['apply']; ?>" />
            </th>
        </tr>

    </table>

</form>

<br />

<div class="information">
<?php foreach ($page->IceInfos as $array): ?>
    <p>
        <strong><?= htEnc($array[0]); ?>:</strong>
        <cite><?= htEnc($array[1]); ?></cite>
    </p>
<?php endforeach; ?>
</div>
