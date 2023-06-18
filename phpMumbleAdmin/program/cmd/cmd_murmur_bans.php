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
* Initiate Murmur connection.
*/
require PMA_DIR_INCLUDES.'iceConnection.inc';
if (! $PMA->murmurMeta->isConnected()) {
    throw new PMA_cmdException();
}

/*
* Get vserver proxy.
*/
if (is_null($prx = $PMA->murmurMeta->getServer($_SESSION['page_vserver']['id']))) {
    throw new PMA_cmdException('Murmur_InvalidServerException');
}

$bansList = $prx->getBans();

/*
* IP & mask sanity
*/
if (isset($cmd->PARAMS['ip'], $cmd->PARAMS['mask'])) {

    /*
    * IP
    */
    $ip = $cmd->PARAMS['ip'];

    if (PMA_ipHelper::isIPv4($ip)) {
        $type = 'ipv4';
        $range = range(1, 32);
    } elseif (PMA_ipHelper::isIPv6($ip)) {
        $type = 'ipv6';
        $range = range(1, 128);
    } else {
        throw new PMA_cmdException('invalid_IP_address');
    }

    /*
    * Mask
    */
    $mask = $cmd->PARAMS['mask'];

    /*
    * Set last range mask if the field if empty.
    */
    if ($mask === '') {
        $mask = end($range);
    }

    $mask = (int)$mask;

    if (! in_array($mask, $range, true)) {
        throw new PMA_cmdException('invalid_bitmask');
    }

    if ($type === 'ipv4') {
        $ipDecimal = PMA_ipHelper::stringToDecimalIPv4($ip);
        $mask = PMA_ipHelper::mask4To6($mask);
    } else {
        $ipDecimal = PMA_ipHelper::stringToDecimalIPv6($ip);
    }

    /*
    * Reason sanity.
    *
    * It's not really possible to add EOL with the mumble client.
    * So replace EOL with a space.
    *
    * DISABLED FOR THE MOMENT.
    */
    // if (isset($cmd->PARAMS['reason'])) {
    //     $cmd->PARAMS['reason'] = replaceEOL($cmd->PARAMS['reason']);
    // }

}

/*
* CMD:
* Add a ban.
*/
if (isset($cmd->PARAMS['addBan'])) {

    $duration = 0;
    $time = time();

    /*
    * Calculate duration.
    */
    if (
        ctype_digit($cmd->PARAMS['hour']) &&
        ctype_digit($cmd->PARAMS['day']) &&
        ctype_digit($cmd->PARAMS['month']) &&
        ctype_digit($cmd->PARAMS['year']) &&
        ! isset($cmd->PARAMS['permanent'])
    ) {
        $hours = (int)$cmd->PARAMS['hour'];
        $days = (int)$cmd->PARAMS['day'];
        $months = (int)$cmd->PARAMS['month'];
        $years = (int)$cmd->PARAMS['year'];
        $timestamp =
            mktime
            (
                $hours,
                date('i', $time),
                date('s', $time),
                $months,
                $days,
                $years
            );
        $duration = $timestamp - $time;
    }

    $add = PMA_MurmurObjectFactory::getBan();
    $add->address = $ipDecimal;
    $add->bits = $mask;
    $add->name = $cmd->PARAMS['name'];
    $add->hash = $cmd->PARAMS['hash'];
    $add->reason = $cmd->PARAMS['reason'];
    $add->start = $time;
    $add->duration = $duration;

    $bansList[] = $add;

    $prx->setBans($bansList);

    if (isset($cmd->PARAMS['kickhim'])) {
        $prx->kickUser($_SESSION['page_vserver']['uSess']['id'], $cmd->PARAMS['reason']);
        unset($_SESSION['page_vserver']['uSess']);
    }

/*
* CMD:
* Edit a ban.
*/
} elseif (isset($cmd->PARAMS['edit_ban_id'])) {

    $cmd->setRedirection('referer');

    $id = (int)$cmd->PARAMS['edit_ban_id'];

    if (! isset($bansList[$id])) {
        throw new PMA_cmdException('invalid_ban_id');
    }

    /*
    * Workaround:
    * upgrading murmur 1.2.2 to 1.2.3 modify all bans start to "-1".
    */
    if ($bansList[$id]->start === -1) {
        $bansList[$id]->start = time();
    }

    $duration = 0;

    /*
    * Calculate duration.
    */
    if (
        ctype_digit($cmd->PARAMS['hour']) &&
        ctype_digit($cmd->PARAMS['day']) &&
        ctype_digit($cmd->PARAMS['month']) &&
        ctype_digit($cmd->PARAMS['year']) &&
        ! isset($cmd->PARAMS['permanent'])
    ) {
        $start = $bansList[$id]->start;

        $hours = (int)$cmd->PARAMS['hour'];
        $days = (int)$cmd->PARAMS['day'];
        $months = (int)$cmd->PARAMS['month'];
        $years = (int)$cmd->PARAMS['year'];

        $timestamp =
            mktime(
                $hours,
                date('i', $start),
                date('s', $start),
                $months,
                $days,
                $years
            );

        $duration = $timestamp - $start;
    }

    /*
    * Memo:
    * There is no reason to edit hash and start.
    */
    $bansList[$id]->address = $ipDecimal;
    $bansList[$id]->bits = $mask;
    $bansList[$id]->name = $cmd->PARAMS['name'];
    $bansList[$id]->reason = $cmd->PARAMS['reason'];
    $bansList[$id]->duration = $duration;

    $prx->setBans($bansList);

/*
* CMD:
* Delete a ban.
*/
} elseif (isset($cmd->PARAMS['delete_ban_id'])) {

    $id = (int)$cmd->PARAMS['delete_ban_id'];

    if (! isset($cmd->PARAMS['confirmed'])) {
        throw new PMA_cmdExitException();
    }

    if (! isset($bansList[$id])) {
        throw new PMA_cmdException('invalid_ban_id');
    }

    unset($bansList[$id]);
    $prx->setBans($bansList);

/*
* CMD:
* Remove a hash.
*/
} elseif (isset($cmd->PARAMS['remove_ban_hash'])) {

    $id = (int)$cmd->PARAMS['remove_ban_hash'];

    if (! isset($bansList[$id])) {
        throw new PMA_cmdException('invalid_ban_id');
    }

    $bansList[$id]->hash = '';
    $prx->setBans($bansList);
}


