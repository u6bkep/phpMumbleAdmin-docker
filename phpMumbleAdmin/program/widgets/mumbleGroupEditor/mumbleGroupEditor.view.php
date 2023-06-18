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

$widget = $PMA->widgets->getDatas('mumbleGroupEditor'); ?>

<div class="toolbar">
    <a href="?action=add_group" onClick="return popup('channelGroupAdd');" class="button">
        <img src="<?= PMA_IMG_ADD_16; ?>" title="<?= $TEXT['add_group']; ?>" alt="" />
    </a>
</div>

<ul id="menuList">
<?php if (empty($widget->groups)): ?>
   <li class="empty">
        <?= $TEXT['no_group'], PHP_EOL; ?>
    </li>
<?php endif;
foreach ($widget->groups as $g): ?>
    <li>
        <a href="?id=<?= $g->href; ?>" class="<?= $g->css; ?>">
            <img src="<?= PMA_IMG_GROUP_16; ?>" class="<?= $g->imgCss; ?>" alt="" />
            <span class="text"><?= htEnc($g->name); ?></span>
        </a>
    </li>
<?php endforeach; ?>
</ul>

<?php
/*
* No group selected. No need to continue.
*/
if (! is_object($widget->group)) {
    return;
}
?>

<div class="toolbar">
<?php if (! $widget->group->inherited): ?>
    <div class="left">
        <a href="?cmd=murmur_groups&amp;deleteGroup" title="<?= $TEXT['del_group']; ?>" class="button">
            <img src="<?= PMA_IMG_TRASH_16; ?>" alt="" />
        </a>
    </div>
<?php else: ?>
    <div class="left"><?= $TEXT['inherited_group']; ?></div>
<?php endif;
if ($widget->group->modified): ?>
    <div class="left">
        <a href="?cmd=murmur_groups&amp;deleteGroup" title="<?= $TEXT['reset_inherited_group']; ?>" class="button">
            <img src="<?= PMA_IMG_RESET_16; ?>" alt="" />
        </a>
    </div>
<?php endif; ?>
    <a href="?cmd=murmur_groups&amp;toggle_group_inherit" title="<?= $TEXT['inherit_parent_group']; ?>" class="button">
        <img src="<?= $widget->group->inheritImg; ?>" alt="" />
    </a>
    <a href="?cmd=murmur_groups&amp;toggle_group_inheritable" title="<?= $TEXT['inheritable_sub']; ?>" class="button">
        <img src="<?= $widget->group->inheritableImg; ?>" alt="" />
    </a>
</div>

<form method="post" class="addGroup">
    <input type="hidden" name="cmd" value="murmur_groups" />
    <fieldset>
        <legend><?= $TEXT['add_user_to_group']; ?></legend>
        <select id="add_user" name="add_user" required="required">
            <option value="">-</option>
<?php foreach ($widget->usersAvailable as $uid => $name): ?>
            <option value="<?= $uid; ?>"><?= $uid.'# '.$name; ?></option>
<?php endforeach; ?>
        </select>
        <input type="submit" class="submit" value="<?= $TEXT['add']; ?>" />
    </fieldset>
</form>

<h4><?= $TEXT['members']; ?></h4>
<ul class="groupMembers">
<?php if (empty($widget->members)): ?>
    <li class="empty">
        <?= $TEXT['empty'], PHP_EOL; ?>
    </li>
<?php endif;
foreach ($widget->members as $m): ?>
    <li>
        <a href="<?= $m->href; ?>" class="button" title="<?= $TEXT['remove_member']; ?>">
            <img src="<?= PMA_IMG_DELETE_16; ?>" alt="" />
        </a>
        <span class="login"><?= htEnc($m->login); ?></span>
    </li>
<?php endforeach; ?>
</ul>

<?php
/*
* No need to show inherited members and excluded members if the group is not inherited.
*/
if (! $widget->group->inherited) {
    return;
}
?>

<h4><?= $TEXT['inherited_members']; ?></h4>
<ul class="groupMembers">
<?php if (empty($widget->inheritedMembers)): ?>
    <li class="empty">
        <?= $TEXT['empty'], PHP_EOL; ?>
    </li>
<?php endif;
foreach ($widget->inheritedMembers as $m): ?>
    <li>
        <a href="<?= $m->href; ?>" class="button" title="<?= $TEXT['exclude_inherited']; ?>">
            <img src="<?= PMA_IMG_GO_DOWN_16; ?>" alt="" />
        </a>
        <span class="login"><?= htEnc($m->login); ?></span>
    </li>
<?php endforeach; ?>
</ul>

<h4><?= $TEXT['excluded_members']; ?></h4>
<ul class="groupMembers">
<?php if (empty($widget->excludedMembers)): ?>
    <li class="empty">
        <?= $TEXT['empty'], PHP_EOL; ?>
    </li>
<?php endif;
foreach ($widget->excludedMembers as $m): ?>
    <li>
        <a href="<?= $m->href; ?>" class="button" title="<?= $TEXT['remove_excluded']; ?>">
            <img src="<?= PMA_IMG_GO_UP_16; ?>" alt="" />
        </a>
        <span class="login"><?= htEnc($m->login); ?></span>
    </li>
<?php endforeach; ?>
</ul>
