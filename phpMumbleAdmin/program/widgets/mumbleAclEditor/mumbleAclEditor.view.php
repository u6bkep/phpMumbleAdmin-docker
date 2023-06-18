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

$widget = $PMA->widgets->getDatas('mumbleAclEditor'); ?>

<div class="toolbar">
<?php if ($page->channelObj->id > 0): ?>
    <div class="left">
        <a href="?cmd=murmur_acl&amp;toggle_inherit_acl" class="button" title="<?= $widget->inheritText; ?>">
            <img src="<?= $widget->inheritImg; ?>" alt="" />
        </a>
    </div>
<?php endif; ?>
    <a href="?cmd=murmur_acl&amp;add_acl" class="button" title="<?= $TEXT['add_acl']; ?>">
        <img src="<?= PMA_IMG_ADD_16; ?>" alt="" />
    </a>
</div>

<ul id="menuList">
<?php foreach ($widget->aclList as $acl): ?>
    <li>
        <a href="?acl=<?= $acl->href; ?>" class="<?= $acl->css; ?>">
            <img src="<?= $acl->img; ?>" alt="" />
            <span class="text"><?= htEnc($acl->name); ?></span>
<?php if ($acl->isDefault): ?>
            <span>(<?= $TEXT['default_acl']; ?>)</span>
<?php elseif ($acl->showAsSuperUserRu): ?>
            <span>(<?= pmaGetClassName(PMA_USER_SUPERUSER_RU); ?>)</span>
<?php endif; ?>
        </a>
    </li>
<?php endforeach; ?>
</ul>

<?php
/*
* No ACL selected. No need to continue.
*/
if (! is_object($widget->Acl)) {
    return;
}
?>

<div class="toolbar">
<?php if (! $widget->Acl->isDisabled): ?>
    <div class="left">
        <a href="?cmd=murmur_acl&amp;delete_acl" class="button" title="<?= $TEXT['del_rule']; ?>">
            <img src="<?= PMA_IMG_TRASH_16; ?>" alt="" />
        </a>
    </div>
    <a href="?cmd=murmur_acl&amp;down_acl" class="button" title="<?= $TEXT['down_rule']; ?>">
        <img src="<?= PMA_IMG_GO_DOWN_16; ?>" alt="" />
    </a>
    <a href="?cmd=murmur_acl&amp;up_acl" class="button" title="<?= $TEXT['up_rule']; ?>">
        <img src="<?= PMA_IMG_GO_UP_16; ?>" alt="" />
    </a>
<?php endif; ?>
</div>

<form class="<?= $widget->tableCss; ?>" method="post" onSubmit="return isFormModified(this);">

    <input type="hidden" name="cmd" value="murmur_acl" />
    <input type="hidden" name="edit_acl" />

    <fieldset <?= HTML::disabled($widget->Acl->isDisabled); ?> id="ACL">

        <select id="groups" name="group" onChange="unselect('users')">
            <option value=""><?= $TEXT['select_group']; ?></option>
            <optgroup label="System">
                <option value="all">all</option>
                <option value="auth">auth</option>
                <option value="in">in</option>
                <option value="sub">sub</option>
                <option value="out">out</option>
                <option value="~in">~in</option>
                <option value="~sub">~sub</option>
                <option value="~out">~out</option>
            </optgroup>
            <optgroup label="Custom">
<?php foreach ($widget->groupList as $group): ?>
                <option value="<?= $group; ?>"><?= htEncSpace(cutLongString($group, 40)); ?></option>
<?php endforeach; ?>
            </optgroup>
        </select>

        <select id="users" name="user" onChange="unselect('groups')">
            <option value=""><?= $TEXT['select_user']; ?></option>
<?php foreach ($widget->registeredUsers as $uid => $login): ?>
            <option value="<?= $uid; ?>"><?= $uid.' # '.htEncSpace(cutLongString($login, 40)); ?></option>
<?php endforeach; ?>
        </select>

        <table>

            <tr>
                <th colspan="2">
                    <label for="applyHere"><?= $TEXT['apply_this_channel']; ?></label>
                </th>
                <td>
                    <input type="checkbox" id="applyHere" name="applyHere" <?= HTML::chked($widget->Acl->applyHere); ?> />
                </td>
            </tr>

            <tr>
                <th colspan="2">
                    <label for="applySubs"><?= $TEXT['apply_sub_channel']; ?></label>
                </th>
                <td>
                    <input type="checkbox" id="applySubs" name="applySubs" <?= HTML::chked($widget->Acl->applySubs); ?> />
                </td>
            </tr>

            <tr>
                <th><?= $TEXT['permissions']; ?></th>
                <th><?= $TEXT['deny']; ?></th>
                <th><?= $TEXT['allow']; ?></th>
            </tr>

<?php foreach ($widget->permissions as $p):
if (is_string($p)): ?>
            <tr>
                <th colspan="3"><?= $TEXT['specific_root']; ?></th>
            </tr>
<?php else: ?>

            <tr>
                <th><?= $p->desc; ?></th>
                <td>
                    <input type="checkbox" name="DENY[<?= $p->bit; ?>]" value="<?= $p->bit; ?>"
                    <?= HTML::chked($p->deny); ?> onClick="uncheck('ALLOW[<?= $p->bit; ?>]')" />
                </td>
                <td>
                    <input type="checkbox" name="ALLOW[<?= $p->bit; ?>]" value="<?= $p->bit; ?>"
                    <?= HTML::chked($p->allow); ?> onClick="uncheck('DENY[<?= $p->bit; ?>]')" />
                </td>
            </tr>
<?php endif;
endforeach; ?>

            <tr>
                <th colspan="3">
                    <input type="submit" value="<?= $TEXT['apply']; ?>" />
                </th>
            </tr>

        </table>

    </fieldset>

</form>
