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

if (! isset($page->sessionObj->certBlob)) {
    $PMA->messageError('illegal_operation');
    throw new PMA_widgetException();
}

/*
* DER to PEM transformation.
*/
$encode64 = base64_encode($page->sessionObj->certBlob);

/*
* PEM must be 65 characters per line.
*/
$lines = str_split($encode64, 65);
$body = join(PHP_EOL, $lines);

/*
*
*/
$certificate = '-----BEGIN CERTIFICATE-----'.PHP_EOL;
$certificate .= $body.PHP_EOL;
$certificate .= '-----END CERTIFICATE-----'.PHP_EOL;

/*
* Setup certificate widget
*/
$PMA->widgets->newModule('certificate');

$cert = new PMA_certificate();
$cert->setPEM($certificate);

require $PMA->widgets->getControllerPath('certificate');

$PMA->widgets->saveDatas('certificate', $cert);
