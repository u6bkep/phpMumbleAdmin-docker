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

class PMA_MurmurAclHelper
{
    /*
    * Remove inherited ACLs:
    *
    * Do not add inherited ACLs as new ACL
    * with Murmur_server::setACL() method.
    */
    public static function rmInheritedAcl(&$aclList)
    {
        foreach ($aclList as $key => $acl) {
            if ($acl->inherited) {
                unset($aclList[$key]);
            }
        }
    }

    /*
    * Remove inherited groups:
    *
    * Do not remove the inherited flag of a group
    * with Murmur_server::setACL() method.
    *
    * @param $keepKey - do not  remove this group.
    */
    public static function rmInheritedGroups(&$groupList, $keepKey = null)
    {
        foreach ($groupList as $key => $group) {
            if ($keepKey !== $key && $group->inherited) {
                unset($groupList[$key]);
            }
        }
    }

    /*
    *
    * Check if a registered user has SuperUserRu rights
    *
    * @param $uid - Mumble user ID.
    * @return boolean
    */
    public static function isSuperUserRu($uid, $aclList)
    {
        $isSuperUserRu = false;

        if ($uid > 0) {
            foreach ($aclList as $acl) {
                if ($acl->userid === $uid) {
                    /*
                    * Memo: continue on false,
                    * maybe the user has more than one ACL.
                    */
                    if (self::isSuperUserRuRule($acl)) {
                        $isSuperUserRu = true;
                        break;
                    }
                }
            }
        }
        return $isSuperUserRu;
    }

    /*
    * Check if a Murmur ACL match for SuperUserRu rights
    *
    * @return boolean
    */
    public static function isSuperUserRuRule($acl)
    {
        return (
            $acl->allow & Murmur_PermissionWrite
            && $acl->userid > 0
            && $acl->applyHere
            && $acl->applySubs
        );
    }

    /*
    * Check if a Murmur ACL rule is a token
    *
    * @return Bool
    */
    public static function isToken($acl)
    {
        return ($acl->userid === -1 && substr($acl->group, 0, 1) === '#');
    }

    /*
    * Check if a Murmur ACL rule is a "deny all" added with a token
    *
    * @return Bool
    */
    public static function isDenyAllToken($acl)
    {
        return (
            $acl->group === 'all'
            && $acl->applyHere
            && $acl->applySubs
            && $acl->deny === 908
        );
    }

    /*
    * Count an array of bitmasks
    *
    * @return integer
    */
    public static function bitmasksCount(array $array)
    {
        $addition = 0;
        foreach ($array as $bit) {
            $addition += (int)$bit;
        }
        return $addition;
    }
}
