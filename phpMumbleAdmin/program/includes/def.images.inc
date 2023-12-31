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

define('PMA_FILE_SANDBOX', PMA_DIR_SANDBOXS.'sandbox_'.session_id().'.html');
define('PMA_FILE_SANDBOX_RELATIVE', str_replace(PMA_ROOT_PATH, '', PMA_FILE_SANDBOX));

define('PMA_DIR_FLAGS',         'images/flags/');

define('PMA_IMG_SPACE_16',      'images/pma/space.png');

define('PMA_IMG_ONLINE_16',     'images/pma/online_16.png');
define('PMA_IMG_OFFLINE_16',    'images/pma/offline_16.png');

define('PMA_IMG_ADD_16',        'images/tango/add_16.png');
define('PMA_IMG_ADD_22',        'images/tango/add_22.png');
define('PMA_IMG_DELETE_16',     'images/tango/delete_16.png');
define('PMA_IMG_DELETE_22',     'images/tango/delete_22.png');

define('PMA_IMG_TRASH_16',      'images/tango/trash_16.png');
//define('PMA_IMG_TRASH_16',      'images/cheser/trash_16.png'); // NEW ftf mb

define('PMA_IMG_LOCKED_16',     'images/cheser/locked_16.png');
define('PMA_IMG_UNLOCKED_16',   'images/cheser/unlocked_16.png');

define('PMA_IMG_CANCEL_12',     'images/gei/cancel_12.png');
define('PMA_IMG_CANCEL_16',     'images/gei/cancel_16.png');
define('PMA_IMG_CANCEL_22',     'images/gei/cancel_22.png');

define('PMA_IMG_MSG_16',        'images/cheser/message_16.png');
define('PMA_IMG_MSG_22',        'images/cheser/message_22.png');

define('PMA_IMG_PW_16',         'images/gei/key_16.png');
define('PMA_IMG_PW_22',         'images/gei/key_22.png');

define('PMA_IMG_CONN_16',       'images/tango/connection_16.png');
define('PMA_IMG_CONN_22',       'images/tango/connection_22.png');

define('PMA_IMG_RESET_16',      'images/gei/hot_16.png');
//define('PMA_IMG_RESET_16',      'images/tango/clear_16.png'); // OLD

define('PMA_IMG_EDIT_16',       'images/tango/edit_16.png');
define('PMA_IMG_EDIT_22',       'images/tango/edit_22.png');

define('PMA_IMG_INFO_12',       'images/gei/info_12.png');
define('PMA_IMG_INFO_16',       'images/gei/info_16.png');
define('PMA_IMG_INFO_22',       'images/gei/info_22.png');

define('PMA_IMG_GROUP_16',      'images/tango/group_16.png');

define('PMA_IMG_OK_16',         'images/gei/ok_16.png');

define('PMA_IMG_WHOIS_22',      'images/tango/whois_22.png');

define('PMA_IMG_REFRESH_16',     'images/cheser/refresh_16.png');

define('PMA_IMG_TOGGLE_IN_16',  'images/cheser/toggle_in_16.png');
define('PMA_IMG_TOGGLE_OUT_16', 'images/cheser/toggle_out_16.png');
// define('PMA_IMG_TOGGLE_IN_16',  'images/tango/toggle_in_16.png'); // OLD
// define('PMA_IMG_TOGGLE_OUT_16', 'images/tango/toggle_out_16.png'); // OLD

define('PMA_IMG_GO_UP_16',      'images/tango/step_16.png');
define('PMA_IMG_GO_DOWN_16',    'images/tango/step_inv_16.png');

define('PMA_IMG_ARROW_UP',      'images/pma/arrowSmall_up.png');
define('PMA_IMG_ARROW_DOWN',    'images/pma/arrowSmall_down.png');
