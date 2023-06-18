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
* Workaround for php-Ice 3.4
*
* Ice.php and slices php definitions files require to be loaded
* in the global scope.
* See:
* http://www.zeroc.com/forums/help-center/5200-no-object-found-icephp_definestruct.html
*/

if ((PMA_ICE_VERSION_INT >= 30400 && PMA_ICE_VERSION_INT < 30500) || (PMA_ICE_VERSION_INT >= 30700 && PMA_ICE_VERSION_INT < 30800)) {

    $PMA->debug('Ice 3.4/3.7 workaround invoked', 2);
    $slicePhp = $PMA->userProfile['slice_php'];

    if (1 === @include('Ice.php')) {
        if (is_file($file = PMA_DIR_SLICE_PHP_CUSTOM.$slicePhp)) {
            include $file;
        } elseif (is_file($file = PMA_DIR_SLICE_PHP.$slicePhp)) {
            include $file;
        }
    }

    require PMA_DIR_INCLUDES.'def.Murmur.inc';
}
