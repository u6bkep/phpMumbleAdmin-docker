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

/*
* Setup commons.
*/
$chanel_id = $_SESSION['page_vserver']['cid'];

$prx->getACL($chanel_id, $aclList, $groupList, $inherit);

$totalAclObjects = count($aclList);
$tlastACLkey = $totalAclObjects - 1;

$aclHlp = new PMA_MurmurAclHelper();

$aclHlp::rmInheritedAcl($aclList);
$aclHlp::rmInheritedGroups($groupList);

/*
* CMD:
* Toggle inherit ACL from parent.
*/
if (isset($cmd->PARAMS['toggle_inherit_acl'])) {

    $inherit = ! $inherit;

    if (! $inherit) {
        // Check if we have selected an inherited ACL, and unselect if true.
        if (
            isset($_SESSION['page_vserver']['aclID'])
            && $_SESSION['page_vserver']['aclID'] !== 'default'
            && ! isset($aclList[$_SESSION['page_vserver']['aclID']])
        ) {
            unset($_SESSION['page_vserver']['aclID']);
        }
    }

    /*
    * update ACL.
    */
    $prx->setACL($chanel_id, $aclList, $groupList, $inherit);

    throw new PMA_cmdExitException();

/*
* CMD:
* Add an ACL.
*/
} elseif (isset($cmd->PARAMS['add_acl'])) {

    $new = PMA_MurmurObjectFactory::getAcl();
    $new->group = 'all';
    $new->userid = -1;
    $new->applyHere = true;
    $new->applySubs = true;
    $new->inherited = false;
    $new->allow = 0;
    $new->deny = 0;

    $aclList[] = $new;

    /*
    * update ACL.
    */
    $prx->setACL($chanel_id, $aclList, $groupList, $inherit);

    /*
    * Select the new ACL.
    */
    $_SESSION['page_vserver']['aclID'] = (int)$totalAclObjects;

    throw new PMA_cmdExitException();
}

/*
* Sanity
* Common for commandes which require a valid ACL object.
*/
if (! isset($_SESSION['page_vserver']['aclID']) OR ! is_int($_SESSION['page_vserver']['aclID'])) {
    throw new PMA_cmdException('invalid_acl_id');
}

/*
* Setup current acl id.
*/
$aclID = $_SESSION['page_vserver']['aclID'];

if (! isset($aclList[$aclID])) {
    throw new PMA_cmdException('invalid_acl_id');
}

/*
* Setup current acl object.
*/
$acl = $aclList[$aclID];

/*
* Sanity.
* Deny to SuperUserRu users the edition of the rule which make them
* a SuperUserRu.
*/
if (
    $chanel_id === 0
    && $PMA->user->is(PMA_USER_SUPERUSER_RU)
    && $aclHlp::isSuperUserRuRule($acl)
) {
    throw new PMA_cmdException('illegal_operation');
}

/*
* CMD:
* Edit an ACL.
*/
if (isset($cmd->PARAMS['edit_acl'])) {

    // Change group
    if ($cmd->PARAMS['group'] !== '' && $cmd->PARAMS['user'] === '') {
        $acl->group = $cmd->PARAMS['group'];
        $acl->userid = -1;
    }
    // Change user
    if (ctype_digit($cmd->PARAMS['user'])) {
        $acl->userid =  (int)$cmd->PARAMS['user'];
        $acl->group = null;
    }
    $acl->applyHere = isset($cmd->PARAMS['applyHere']);
    $acl->applySubs = isset($cmd->PARAMS['applySubs']);
    // Remove ACLs with both allow & deny key.
    if (isset($cmd->PARAMS['ALLOW'], $cmd->PARAMS['DENY'])) {
        foreach ($cmd->PARAMS['ALLOW'] as $key => $value) {
            if (isset($cmd->PARAMS['DENY'][$key])) {
                unset($cmd->PARAMS['ALLOW'][$key], $cmd->PARAMS['DENY'][$key]);
            }
        }
    }
    if (isset($cmd->PARAMS['ALLOW'])) {
        $acl->allow = $aclHlp::bitmasksCount($cmd->PARAMS['ALLOW']);
    } else {
        $acl->allow = 0;
    }
    if (isset($cmd->PARAMS['DENY'])) {
        $acl->deny = $aclHlp::bitmasksCount($cmd->PARAMS['DENY']);
    } else {
        $acl->deny = 0;
    }
    $aclList[$aclID] = $acl;
    $prx->setACL($chanel_id, $aclList, $groupList, $inherit);

/*
* CMD:
* Push up an ACL.
*/
} elseif (isset($cmd->PARAMS['up_acl'])) {

    // Push up only if it's not the first ACL
    reset($aclList);
    if ($aclID !== key($aclList)) {
        $up = $aclID -1;
        $down = $aclID;

        $tmp[$up] = $aclList[$up];
        $tmp[$down] = $aclList[$down];

        $aclList[$up] = $tmp[$down];
        $aclList[$down] = $tmp[$up];

        $prx->setACL($chanel_id, $aclList, $groupList, $inherit);

        $_SESSION['page_vserver']['aclID'] = (int)$up;
    }

/*
* CMD:
* Push down an ACL.
*/
} elseif (isset($cmd->PARAMS['down_acl'])) {

    // Push down only if it's not the last ACL
    if ($aclID !== $tlastACLkey) {
        $up = $aclID;
        $down = $aclID +1;

        $tmp[$up] = $aclList[$up];
        $tmp[$down] = $aclList[$down];

        $aclList[$up] = $tmp[$down];
        $aclList[$down] = $tmp[$up];

        $prx->setACL($chanel_id, $aclList, $groupList, $inherit);

        $_SESSION['page_vserver']['aclID'] = (int)$down;
    }

/*
* CMD:
* Delete an ACL.
*/
} elseif (isset($cmd->PARAMS['delete_acl'])) {

    unset($aclList[$aclID]);

    $prx->setACL($chanel_id, $aclList, $groupList, $inherit);
    /*
    * Stay on the last ACL if we deleted the last one.
    */
    if ($aclID === $tlastACLkey) {
        $_SESSION['page_vserver']['aclID'] = (int)($tlastACLkey - 1);
    }
}

