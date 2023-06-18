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

/*
* Get datas from $prx
*/
$prx->getACL($page->channelObj->id, $aclList, $groupList, $inherit);
/*
* Default channel checkbox variables.
*/
$isDefault = ($page->defaultChannelID === $page->channelObj->id);
/*
* Get the token password.
*/
$password = '';
foreach ($aclList as $acl) {
    if (! $acl->inherited && PMA_MurmurAclHelper::isToken($acl)) {
        $password = substr($acl->group, 1);
        break;
    }
}
/*
* Setup variables.
*/
$widget->set('isDefault', $isDefault);
$widget->set('isDisabled', ($isDefault OR $page->channelObj->temporary));
$widget->id = $page->channelObj->id;
$widget->set('name', $page->channelObj->name);
$widget->set('password', $password);
$widget->set('position', $page->channelObj->position);
$widget->set('desc', $page->channelObj->description);

PMA_sandBoxHelper::create($page->channelObj->description);
