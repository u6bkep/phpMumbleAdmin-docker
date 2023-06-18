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

// Workaround to use the old global constant with Ice 3.7
if (PMA_ICE_VERSION_INT >= 30700) {
    if (! defined('Murmur_PermissionWrite') && defined('\\Murmur\\PermissionWrite')) {
        define('Murmur_PermissionWrite', \Murmur\PermissionWrite);
    }
    if (! defined('Murmur_PermissionTraverse') && defined('\\Murmur\\PermissionTraverse')) {
        define('Murmur_PermissionTraverse', \Murmur\PermissionTraverse);
    }
    if (! defined('Murmur_PermissionEnter') && defined('\\Murmur\\PermissionEnter')) {
        define('Murmur_PermissionEnter', \Murmur\PermissionEnter);
    }
    if (! defined('Murmur_PermissionSpeak') && defined('\\Murmur\\PermissionSpeak')) {
        define('Murmur_PermissionSpeak', \Murmur\PermissionSpeak);
    }
    if (! defined('Murmur_PermissionMuteDeafen') && defined('\\Murmur\\PermissionMuteDeafen')) {
        define('Murmur_PermissionMuteDeafen', \Murmur\PermissionMuteDeafen);
    }
    if (! defined('Murmur_PermissionMove') && defined('\\Murmur\\PermissionMove')) {
        define('Murmur_PermissionMove', \Murmur\PermissionMove);
    }
    if (! defined('Murmur_PermissionMakeChannel') && defined('\\Murmur\\PermissionMakeChannel')) {
        define('Murmur_PermissionMakeChannel', \Murmur\PermissionMakeChannel);
    }
    if (! defined('Murmur_PermissionLinkChannel') && defined('\\Murmur\\PermissionLinkChannel')) {
        define('Murmur_PermissionLinkChannel', \Murmur\PermissionLinkChannel);
    }
    if (! defined('Murmur_PermissionWhisper') && defined('\\Murmur\\PermissionWhisper')) {
        define('Murmur_PermissionWhisper', \Murmur\PermissionWhisper);
    }
    if (! defined('Murmur_PermissionTextMessage') && defined('\\Murmur\\PermissionTextMessage')) {
        define('Murmur_PermissionTextMessage', \Murmur\PermissionTextMessage);
    }
    if (! defined('Murmur_PermissionMakeTempChannel') && defined('\\Murmur\\PermissionMakeTempChannel')) {
        define('Murmur_PermissionMakeTempChannel', \Murmur\PermissionMakeTempChannel);
    }
    if (! defined('Murmur_PermissionKick') && defined('\\Murmur\\PermissionKick')) {
        define('Murmur_PermissionKick', \Murmur\PermissionKick);
    }
    if (! defined('Murmur_PermissionBan') && defined('\\Murmur\\PermissionBan')) {
        define('Murmur_PermissionBan', \Murmur\PermissionBan);
    }
    if (! defined('Murmur_PermissionRegister') && defined('\\Murmur\\PermissionRegister')) {
        define('Murmur_PermissionRegister', \Murmur\PermissionRegister);
    }
    if (! defined('Murmur_PermissionRegisterSelf') && defined('\\Murmur\\PermissionRegisterSelf')) {
        define('Murmur_PermissionRegisterSelf', \Murmur\PermissionRegisterSelf);
    }
}
