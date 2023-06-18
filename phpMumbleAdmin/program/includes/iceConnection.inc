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
* Common Ice connection file.
*/


/*
* Check php_Ice module version.
*/
if (
    PMA_ICE_VERSION_INT > PMA_ICE_VERSION_INT_MIN &&
    PMA_ICE_VERSION_INT < PMA_ICE_VERSION_INT_MAX
) {
    $class = 'PMA_MurmurConnectionIce'.
        PMA_ICE_VERSION_INT_MAJOR.
        PMA_ICE_VERSION_INT_MINOR;
    $phpIceVersionSupported = true;
} else {
    $class = 'PMA_MurmurConnectionIce';
    $phpIceVersionSupported = false;
}

$PMA->murmurConn = new $class();

$PMA->murmurConn->setIcePhpVersion(PMA_ICE_VERSION_INT);
$PMA->murmurConn->isIcePhpVersionSupported($phpIceVersionSupported);

/*
* Setup Murmur connection parameters
*/
if (is_array($PMA->userProfile)) {
    $PMA->murmurConn->setHost($PMA->userProfile['host']);
    $PMA->murmurConn->setPort($PMA->userProfile['port']);
    $PMA->murmurConn->setTimeout($PMA->userProfile['timeout']);
    $PMA->murmurConn->setSecret($PMA->userProfile['secret']);
    $PMA->murmurConn->setSlicePhpPaths(
        PMA_DIR_SLICE_PHP.$PMA->userProfile['slice_php'],
        PMA_DIR_SLICE_PHP_CUSTOM.$PMA->userProfile['slice_php']
    );

    $PMA->murmurMeta->setSecret($PMA->userProfile['secret']);

} else {
    /*
    * Admin hasn't ice profile. Let him know.
    */
    if ($PMA->user->is(PMA_USER_ADMIN)) {
        $PMA->messageError('iceprofiles_admin_none');
    }
}

/*
* Enable debug for ice conn
*/
$PMA->murmurConn->addDebugObs($PMA);

try {
    $PMA->murmurConn->connect();
} catch (PMA_MurmurConnectionIceException $e) {
    $PMA->setIceError($e->getMessage());
}

$PMA->murmurMeta->setMeta($PMA->murmurConn->getMeta());

if ($PMA->murmurMeta->isConnected()) {
    $PMA->murmurMeta->setDefaultConf($PMA->murmurConn->defaultConf);
    $PMA->murmurMeta->setVersion($PMA->murmurConn->version);
}
