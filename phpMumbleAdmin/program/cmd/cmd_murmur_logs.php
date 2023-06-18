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
* User Class Sanity.
*
* Check for user class,
* require PMA_USER_SUPERUSER_RU minimum.
*/
if (! $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
    throw new PMA_cmdException('illegal_operation');
}

/*
* CMD:
* Toggle logs filters.
*/
if (isset($cmd->PARAMS['toggle_log_filter'])) {

    $bitmask = $cmd->PARAMS['toggle_log_filter'];

    if (! ctype_digit($bitmask)) {
        $PMA->debugError('Bitmask must be numerical: '.$bitmask);
        throw new PMA_cmdException('illegal_operation');
    }

    $bitmask = (int)$bitmask;

    /*
    * Get $logsFilters array.
    */
    require PMA_DIR_INCLUDES.'murmurLogsFiltersRules.inc';

    $bitmaskTotal = 0;
    foreach($logsfilters as $filter) {
        $bitmaskTotal += $filter['mask'];
    }

    /*
    * Check if submitted bitmask exist.
    */
    if (! $bitmaskTotal & $bitmask) {
        $PMA->debugError('Bitmask '.$bitmask.' do not exist.');
        throw new PMA_cmdException('illegal_operation');
    }

    $userBitmaskTotal = $PMA->cookie->get('logsFilters');

    /*
    * Add or remove bitmask from the user total.
    */
    if ($bitmask & $userBitmaskTotal) {
        $userBitmaskTotal -= $bitmask;
    } else {
        $userBitmaskTotal += $bitmask;
    }

    $PMA->cookie->set('logsFilters', $userBitmaskTotal);

/*
* CMD:
* Toggle logs strings replace rules.
*/
} elseif (isset($cmd->PARAMS['replace_logs_str'])) {
    $PMA->cookie->set('replace_logs_str', ! $PMA->cookie->get('replace_logs_str'));

/*
* CMD:
* Toggle logs highlight.
*/
} elseif (isset($cmd->PARAMS['toggle_highlight'])) {
    $PMA->cookie->set('highlight_logs', ! $PMA->cookie->get('highlight_logs'));

/*
* CMD:
* Logs search.
*/
} elseif (isset($cmd->PARAMS['logs_search'])) {
    if ($cmd->PARAMS['logs_search'] === '') {
        unset($_SESSION['search']['logs']);
    } else {
        $_SESSION['search']['logs'] = $cmd->PARAMS['logs_search'];
    }

/*
* CMD:
* Reset logs search.
*/
} elseif (isset($cmd->PARAMS['reset_logs_search'])) {
    unset($_SESSION['search']['logs']);
}
