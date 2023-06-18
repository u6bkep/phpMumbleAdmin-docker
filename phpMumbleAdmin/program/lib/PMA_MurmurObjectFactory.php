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
 *
 * Introduced with Ice 3.7, because this version started to use namespace
 * instead of class name.
 * Get Murmur object based on the Ice version.
 */
class PMA_MurmurObjectFactory
{
    public static function getAcl()
    {
        if (PMA_ICE_VERSION_INT < 30700) {
            return new Murmur_acl();
        }
        return new Murmur\Acl();
    }

    public static function getBan()
    {
        if (PMA_ICE_VERSION_INT < 30700) {
            return new Murmur_Ban();
        }
        return new Murmur\Ban();
    }

    public static function getGroup()
    {
        if (PMA_ICE_VERSION_INT < 30700) {
            return new Murmur_Group();
        }
        return new Murmur\Group();
    }

    public static function getTree()
    {
        if (PMA_ICE_VERSION_INT < 30700) {
            return new Murmur_Tree();
        }
        return new Murmur\Tree();
    }

    public static function getUser()
    {
        if (PMA_ICE_VERSION_INT < 30700) {
            return new Murmur_User();
        }
        return new Murmur\User();
    }
}
