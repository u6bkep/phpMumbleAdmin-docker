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
    if (! defined('Murmur_PermissionWrite') && defined('\\MumbleServer\\PermissionWrite')) {
        define('Murmur_PermissionWrite', \MumbleServer\PermissionWrite);
    }
    if (! defined('Murmur_PermissionTraverse') && defined('\\MumbleServer\\PermissionTraverse')) {
        define('Murmur_PermissionTraverse', \MumbleServer\PermissionTraverse);
    }
    if (! defined('Murmur_PermissionEnter') && defined('\\MumbleServer\\PermissionEnter')) {
        define('Murmur_PermissionEnter', \MumbleServer\PermissionEnter);
    }
    if (! defined('Murmur_PermissionSpeak') && defined('\\MumbleServer\\PermissionSpeak')) {
        define('Murmur_PermissionSpeak', \MumbleServer\PermissionSpeak);
    }
    if (! defined('Murmur_PermissionMuteDeafen') && defined('\\MumbleServer\\PermissionMuteDeafen')) {
        define('Murmur_PermissionMuteDeafen', \MumbleServer\PermissionMuteDeafen);
    }
    if (! defined('Murmur_PermissionMove') && defined('\\MumbleServer\\PermissionMove')) {
        define('Murmur_PermissionMove', \MumbleServer\PermissionMove);
    }
    if (! defined('Murmur_PermissionMakeChannel') && defined('\\MumbleServer\\PermissionMakeChannel')) {
        define('Murmur_PermissionMakeChannel', \MumbleServer\PermissionMakeChannel);
    }
    if (! defined('Murmur_PermissionLinkChannel') && defined('\\MumbleServer\\PermissionLinkChannel')) {
        define('Murmur_PermissionLinkChannel', \MumbleServer\PermissionLinkChannel);
    }
    if (! defined('Murmur_PermissionWhisper') && defined('\\MumbleServer\\PermissionWhisper')) {
        define('Murmur_PermissionWhisper', \MumbleServer\PermissionWhisper);
    }
    if (! defined('Murmur_PermissionTextMessage') && defined('\\MumbleServer\\PermissionTextMessage')) {
        define('Murmur_PermissionTextMessage', \MumbleServer\PermissionTextMessage);
    }
    if (! defined('Murmur_PermissionMakeTempChannel') && defined('\\MumbleServer\\PermissionMakeTempChannel')) {
        define('Murmur_PermissionMakeTempChannel', \MumbleServer\PermissionMakeTempChannel);
    }
    if (! defined('Murmur_PermissionKick') && defined('\\MumbleServer\\PermissionKick')) {
        define('Murmur_PermissionKick', \MumbleServer\PermissionKick);
    }
    if (! defined('Murmur_PermissionBan') && defined('\\MumbleServer\\PermissionBan')) {
        define('Murmur_PermissionBan', \MumbleServer\PermissionBan);
    }
    if (! defined('Murmur_PermissionRegister') && defined('\\MumbleServer\\PermissionRegister')) {
        define('Murmur_PermissionRegister', \MumbleServer\PermissionRegister);
    }
    if (! defined('Murmur_PermissionRegisterSelf') && defined('\\MumbleServer\\PermissionRegisterSelf')) {
        define('Murmur_PermissionRegisterSelf', \MumbleServer\PermissionRegisterSelf);
    }
}
