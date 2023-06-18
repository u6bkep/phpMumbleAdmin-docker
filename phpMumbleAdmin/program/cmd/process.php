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

$PMA->debug('CMD invoked', 3);

/*
* Check for Ice-PHP 3.4 workaround.
*/
require PMA_FILE_ICE34_WORKAROUND;

$params = isset($_GET['cmd']) ? $_GET : $_POST;

/*
* Check for a valid cmd parameter.
*/
switch ($params['cmd']) {
    case 'auth':
    case 'config':
    case 'config_admins':
    case 'config_ICE':
    case 'install':
    case 'logout':
    case 'murmur_logs':
    case 'murmur_acl':
    case 'murmur_bans':
    case 'murmur_channel':
    case 'murmur_groups':
    case 'murmur_registrations':
    case 'murmur_settings':
    case 'murmur_users_sessions':
    case 'overview';
    case 'pw_requests':
    case 'routes':
        $cmd = new PMA_cmd();
        break;
    default:
        $cmd = null;
        break;
}

if (is_object($cmd)) {

    $cmd->setParameters($params);

    $PMA->debug('include '.$params['cmd']);

    try {
        require PMA_DIR_CMD.'cmd_'.$params['cmd'].'.php';
    } catch (PMA_cmdExitException $e) {
        // Do nothing
    } catch (PMA_cmdException $e) {
        if ($e->getMessage() !== '') {
            $PMA->messageError($e->getMessage());
        }
    } catch (Exception $e) {
        pmaExceptionsOperations($e);
    }

    /*
    * Log user actions.
    */
    if (
        ! $PMA->user->is(PMA_USER_SUPERADMIN) OR
        $PMA->config->get('pmaLogs_SA_actions')
    ) {

        if (is_int($PMA->user->adminID)) {
            $user = $PMA->user->adminID.'# '.$PMA->user->login;
        } else {
            $user = pmaGetClassName($PMA->user->class);
        }

        foreach ($cmd->getUserActions() as $message) {
            $PMA->log('action.info', $user.' - '.$message);
        }
    }

    /*
    * Shutdown.
    */
    if ($PMA->config->get('debug') > 0) {
        $_SESSION['cmd_stats']['duration'] = PMA_statsHelper::duration(PMA_STARTED);
        $_SESSION['cmd_stats']['memory'] = PMA_statsHelper::memory();
        $_SESSION['cmd_stats']['ice'] = PMA_statsHelper::iceQueries();
    }
    /*
    * Always redirect after a command.
    */
    if ($cmd->getRedirection() === 'referer') {
        $redirection = $_SESSION['referer'];
    } else {
        $redirection = $cmd->getRedirection();
    }
    $PMA->redirection($redirection);
}
