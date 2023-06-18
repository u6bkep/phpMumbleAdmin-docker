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
* TODO:
* session.auto_start = off (check for)
* file_uploads = on/off (check for)
*/

/*
* PMA project.
*/
define('PMA_PROJECT_NAME', 'phpMumbleAdmin');
define('PMA_VERSION_STR', '0.4.5');
define('PMA_VERSION_INT', 45);
define('PMA_VERSION_DESC', 'WORK');
define('PMA_VERSION_FULL', PMA_VERSION_STR.' - '.PMA_VERSION_DESC);
define('PMA_PROJECT_URL', 'http://sourceforge.net/projects/phpmumbleadmin/');
/*
* PHP_VERSION_ID is available since PHP 5.2.7.
*/
if (! defined('PHP_VERSION_ID')) {
   define('PHP_VERSION_ID', 0);
}
/*
* Default socket timeout.
* The default timeout is 60 secondes, it's really too long.
*/
if (ini_get('default_socket_timeout') > 10) {
    @ini_set('default_socket_timeout', 10);
}
/*
* Setup PMA URLs
*/
$http = getenv('HTTPS') === 'on' ? 'https' : 'http';
$port = $_SERVER['SERVER_PORT'];
$port = ($port !== '80' && $port !== '443') ? ':'.$port : '';
define('PMA_HTTP_HOST', $http.'://'.$_SERVER['HTTP_HOST'].$port);
define('PMA_HTTP_PATH', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
/*
* Get operating system.
*/
define('PMA_OS', strToLower(php_uname('s')));
/*
* Check if suhosin.cookie.encrypt is enable.
*/
define('PMA_COOKIE_ENCRYPTED', (ini_get('suhosin.cookie.encrypt') === '1'));
/*
* Define php_Ice_module int and str version supported by PMA.
* Minimum and maximum.
*/
define('PMA_ICE_VERSION_STR_MIN', 3.4);
define('PMA_ICE_VERSION_STR_MAX', 3.7);
define('PMA_ICE_VERSION_INT_MIN', 30400);
define('PMA_ICE_VERSION_INT_MAX', 30800);
/*
* Get php-Ice versions (string & integer).
*/
if (function_exists('Ice_stringversion') && function_exists('Ice_intversion')) {
    define('PMA_ICE_VERSION_STR', Ice_stringversion());
    define('PMA_ICE_VERSION_INT', Ice_intversion());
    define('PMA_ICE_VERSION_INT_MAJOR', (int)substr(PMA_ICE_VERSION_INT, 0, 1));
    define('PMA_ICE_VERSION_INT_MINOR', (int)substr(PMA_ICE_VERSION_INT, 1, 2));
    define('PMA_ICE_VERSION_INT_PATCH', (int)substr(PMA_ICE_VERSION_INT, 3, 2));
} elseif (function_exists('\Ice\stringversion') && function_exists('\Ice\intversion')) {
    define('PMA_ICE_VERSION_STR', \Ice\stringversion());
    define('PMA_ICE_VERSION_INT', \Ice\intversion());
    define('PMA_ICE_VERSION_INT_MAJOR', (int)substr(PMA_ICE_VERSION_INT, 0, 1));
    define('PMA_ICE_VERSION_INT_MINOR', (int)substr(PMA_ICE_VERSION_INT, 1, 2));
    define('PMA_ICE_VERSION_INT_PATCH', (int)substr(PMA_ICE_VERSION_INT, 3, 2));
} else {
    define('PMA_ICE_VERSION_STR', 'Not found');
    define('PMA_ICE_VERSION_INT', 0);
    define('PMA_ICE_VERSION_INT_MAJOR', 0);
    define('PMA_ICE_VERSION_INT_MINOR', 0);
    define('PMA_ICE_VERSION_INT_PATCH', 0);
}

define('PMA_DIR_SLICE_PHP',             PMA_DIR_LIB.'MurmurSlicesPhp/ice'.
                                            PMA_ICE_VERSION_INT_MAJOR.
                                            PMA_ICE_VERSION_INT_MINOR.'/'
);
define('PMA_DIR_SLICE_PHP_CUSTOM',      PMA_ROOT_PATH.'slicesPhp/ice'.
                                            PMA_ICE_VERSION_INT_MAJOR.
                                            PMA_ICE_VERSION_INT_MINOR.'/'
);

/*
* Max file size for a certificate in bytes (20kb).
* A certificate bigger than that is suspicious.
*/
define('MAX_FILE_SIZE_CERT', 20480);
