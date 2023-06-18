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
* Process
*/
$dateTimeFormat = $DATES->getDateTimeFormat();

if (! function_exists('openssl_x509_parse')) {
    $widget->error('php-openssl module is not installed');
    return;
}

$parse = openssl_x509_parse($widget->getPem(), false);

if (! is_array($parse)) {
    $widget->error('invalid certificate');
    return;
}

/*
* remove duplicate/useless entries.
*/
unset($parse['purposes'], $parse['validFrom'], $parse['validTo']);

/*
* Put orphelin values into the array key "OTHER".
*/
foreach ($parse as $key => $value) {
    if (! is_array($value)) {
        $parse['OTHERS'][$key] = $value;
        unset($parse[$key]);
    }
}

/*
* Add titles and values.
*/
foreach ($parse as $key => $array) {
    $widget->addTitle($key);
    ksort($array);
    foreach ($array as $k => $v) {
        if ($k === 'validFrom_time_t') {
            $k = 'Valid from';
            $v = strftime($dateTimeFormat, $v);
        } elseif ($k === 'validTo_time_t') {
            $k = 'Valid to';
            $v =  strftime($dateTimeFormat, $v);
        }
        $widget->addData($k, $v);
    }
}
