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

define('PMA_DIR_CSS',                   PMA_ROOT_PATH.'css/');
define('PMA_DIR_CONFIG',                PMA_ROOT_PATH.'config/');
define('PMA_DIR_HTMLCACHE',             PMA_ROOT_PATH.'cache/');
define('PMA_DIR_LANGUAGES',             PMA_ROOT_PATH.'languages/');
define('PMA_DIR_LOGS',                  PMA_ROOT_PATH.'logs/');

define('PMA_DIR_SANDBOXS',              PMA_DIR_HTMLCACHE);
define('PMA_DIR_AVATARS',               PMA_DIR_HTMLCACHE);

define('PMA_DIR_CACHE',                 PMA_DIR_PROG.'cache/');
define('PMA_DIR_CMD',                   PMA_DIR_PROG.'cmd/');
define('PMA_DIR_FUNCTIONS',             PMA_DIR_PROG.'functions/');
define('PMA_DIR_LIB',                   PMA_DIR_PROG.'lib/');
define('PMA_DIR_INCLUDES',              PMA_DIR_PROG.'includes/');
define('PMA_DIR_PAGES',                 PMA_DIR_PROG.'pages/');
define('PMA_DIR_POPUPS',                PMA_DIR_PROG.'popups/');
define('PMA_DIR_ROUTES',                PMA_DIR_PROG.'routes/');
define('PMA_DIR_WIDGETS',               PMA_DIR_PROG.'widgets/');

define('PMA_DIR_SESSIONS',              PMA_DIR_CACHE.'sessions/');



define('PMA_FILE_LOGS',                 PMA_DIR_LOGS.'log.txt');
define('PMA_FILE_ICE34_WORKAROUND',     PMA_DIR_INCLUDES.'ice34Workaround.inc');

// Relative paths
define('PMA_DIR_AVATARS_RELATIVE', str_replace(PMA_ROOT_PATH, '', PMA_DIR_AVATARS));
/*
* Define file iframe in def.images.inc because we need a valid session id.
*/
