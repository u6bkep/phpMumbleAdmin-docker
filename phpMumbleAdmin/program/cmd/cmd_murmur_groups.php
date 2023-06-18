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
* User Class Sanity.
*
* Check for user class,
* require PMA_USER_SUPERUSER_RU minimum.
*/
if (! $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
    throw new PMA_cmdException('illegal_operation');
}

/*
* Initiate Murmur connection.
*/
require PMA_DIR_INCLUDES.'iceConnection.inc';
if (! $PMA->murmurMeta->isConnected()) {
    throw new PMA_cmdException();
}

/*
* Get vserver proxy.
*/
if (is_null($prx = $PMA->murmurMeta->getServer($_SESSION['page_vserver']['id']))) {
    throw new PMA_cmdException('Murmur_InvalidServerException');
}

$cid = $_SESSION['page_vserver']['cid'];

$prx->getACL($cid, $aclList, $groupList, $inheritParent);

PMA_MurmurAclHelper::rmInheritedAcl($aclList);

/*
* CMD:
* Add a group.
*/
if (isset($cmd->PARAMS['add_group'])) {

    $name = $cmd->PARAMS['add_group'];

    if ($name === '') {
        throw new PMA_cmdException('empty_name');
    }

    /*
    * Mumble add groups names in lower case, so do it.
    */
    $name = strToLower($name);

    PMA_MurmurAclHelper::rmInheritedGroups($groupList);

    $new = PMA_MurmurObjectFactory::getGroup();
    $new->name = $name;
    $new->inherited = false;
    $new->inherit = true;
    $new->inheritable = true;
    $new->add = array();
    $new->members = array();
    $new->remove = array();

    $groupList[] = $new;

    $prx->setACL($cid, $aclList, $groupList, $inheritParent);

    /*
    * Murmur reindex keys of groups after setACL()
    * Get the group list a second time to find the new group and select it.
    */
    $prx->getACL($cid, $aclList, $groupList, $inheritParent);

    foreach ($groupList as $key => $group) {
        if ($group->name === $name) {
            $_SESSION['page_vserver']['groupID'] = $key;
            break;
        }
    }

    throw new PMA_cmdExitException();
}

/*
* Valid group ID sanity.
*
* Memo: addGroup do not require this check.
*/
if (! isset($_SESSION['page_vserver']['groupID'])) {
    throw new PMA_cmdException('invalid_group_id');
}

$gid = $_SESSION['page_vserver']['groupID'];

if (! isset($groupList[$gid])) {
    throw new PMA_cmdException('invalid_group_id');
}

PMA_MurmurAclHelper::rmInheritedGroups($groupList, $gid);

/*
* CMD:
* Delete a group.
*/
if (isset($cmd->PARAMS['deleteGroup'])) {

    $keepname = $groupList[$gid]->name;

    unset($groupList[$gid], $_SESSION['page_vserver']['groupID']);
    $prx->setACL($cid, $aclList, $groupList, $inheritParent);

    /*
    * If we reset an inherited group, re-select it.
    */
    $prx->getACL($cid, $aclList, $groupList, $inheritParent);

    foreach ($groupList as $key => $group) {
        if ($group->name === $keepname) {
            $_SESSION['page_vserver']['groupID'] = $key;
            break;
        }
    }

/*
* CMD:
* Toggle inherit flag.
*/
} elseif (isset($cmd->PARAMS['toggle_group_inherit'])) {
    $groupList[$gid]->inherit = ! $groupList[$gid]->inherit;
    $prx->setACL($cid, $aclList, $groupList, $inheritParent);

/*
* CMD:
* Toggle inheritable flag.
*/
} elseif (isset($cmd->PARAMS['toggle_group_inheritable'])) {
    $groupList[$gid]->inheritable = ! $groupList[$gid]->inheritable;
    $prx->setACL($cid, $aclList, $groupList, $inheritParent);

/*
* CMD:
* Add an user.
*
* TODO: check that the variable $id is a valid registered user on the vserver.
*/
} elseif (isset($cmd->PARAMS['add_user'])) {

    $id = $cmd->PARAMS['add_user'];

    if (! ctype_digit($id)) {
        throw new PMA_cmdException('invalid_numerical');
    }

    $groupList[$gid]->add[] = (int)$id;
    $prx->setACL($cid, $aclList, $groupList, $inheritParent);

/*
* CMD:
* Remove a member.
*/
} elseif (isset($cmd->PARAMS['removeMember'])) {

    $id = $cmd->PARAMS['removeMember'];

    if (! ctype_digit($id)) {
        throw new PMA_cmdException('invalid_numerical');
    }

    $id = (int)$id;

    foreach ($groupList[$gid]->add as $key => $uid) {
        if ($uid === $id) {
            unset($groupList[$gid]->add[$key]);
            $prx->setACL($cid, $aclList, $groupList, $inheritParent);
            break;
        }
    }

/*
* CMD:
* Exclude a member.
*/
} elseif (isset($cmd->PARAMS['excludeMember'])) {

    $id = $cmd->PARAMS['excludeMember'];

    if (! ctype_digit($id)) {
        throw new PMA_cmdException('invalid_numerical');
    }

    $id = (int)$id;

    // Do not exclude "non-inherited" members.
    if (in_array($id, $groupList[$gid]->add, true)) {
        throw new PMA_cmdException('non_inherited_member');
    }

    // Check for a valid inherited uid
    foreach ($groupList[$gid]->members as $key => $uid) {
        if ($uid === $id) {
            $groupList[$gid]->remove[] = $id;
            $prx->setACL($cid, $aclList, $groupList, $inheritParent);
            break;
        }
    }

/*
* CMD:
* Remove excluded member.
*/
} elseif (isset($cmd->PARAMS['removeExcluded'])) {

    $id = $cmd->PARAMS['removeExcluded'];

    if (! ctype_digit($id)) {
        throw new PMA_cmdException('invalid_numerical');
    }

    $id = (int)$id;

    foreach ($groupList[$gid]->remove as $key => $uid) {
        if ($uid === $id) {
            unset($groupList[$gid]->remove[$key]);
            $prx->setACL($cid, $aclList, $groupList, $inheritParent);
            break;
        }
    }
}

