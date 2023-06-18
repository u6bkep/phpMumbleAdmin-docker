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

pmaLoadLanguage('logs');

$PMA->widgets->newModule('logs');

$page->logs = new PMA_logsPMA();
$page->logs->setAllowHighlight($PMA->cookie->get('highlight_pmaLogs'));

$pmaLogs = @file(PMA_FILE_LOGS);
if (! is_array($pmaLogs)) {
    $pmaLogs = array();
    $PMA->messageError(PMA_FILE_LOGS.' not found.');
}
$pmaLogs = array_reverse($pmaLogs);
/*
* Setup filters ( based on subtab route).
*/
if ($PMA->router->getRoute('subtab') !== 'all') {
    $page->logs->setsearchLevelPattern($PMA->router->getRoute('subtab'));
}
/*
* Setup highLights.
*/
if ($page->logs->isAllowHighlight()) {
    $page->logs->addHighlightLevelRule('Lauth', 'auth.info');
    $page->logs->addHighlightLevelRule('Lerror', 'auth.error');
    $page->logs->addHighlightLevelRule('Ladmin', 'action.info');
    $page->logs->addHighlightLevelRule('Linfo', '.info');
    $page->logs->addHighlightLevelRule('Lwarn', '.warn');
    $page->logs->addHighlightLevelRule('Lerror', '.error');

    $page->logs->addHighlightRule('Lauth', 'Successful login');
    $page->logs->addHighlightRule('Lerror', 'Login error');
    $page->logs->addHighlightRule('Lerror', 'Password error');
    $page->logs->addHighlightRule('Lwarn', 'Virtual server deleted');
    $page->logs->addHighlightRule('Lwarn', 'Server stopped');
    $page->logs->addHighlightRule('Linfo', 'Virtual server reseted');
    $page->logs->addHighlightRule('Ladmin', 'Virtual server created');
    $page->logs->addHighlightRule('Linfo', 'Server started');
    $page->logs->addHighlightRule('Lwarn', 'profile deleted');
    $page->logs->addHighlightRule('Linfo', 'profile created');
    $page->logs->addHighlightRule('Ladmin', 'profile updated');
    $page->logs->addHighlightRule('Lwarn', 'Admin account deleted');
    $page->logs->addHighlightRule('Linfo', 'Admin account created');
    $page->logs->addHighlightRule('Ladmin', 'Admin account updated');
}

if ($PMA->config->get('pmaLogs_keep') > 0) {
    $cleanLogs = true;
    $keepLogsDuration = $PMA->config->get('pmaLogs_keep' )* 24 * 3600;
} else {
    $cleanLogs = false;
}

foreach ($pmaLogs as $key => $line) {
    /*
    * MEMO:
    * [0]timestamp ::: [1]localtime ::: [2]logLvl ::: [3]ip ::: [4]txt ::: [5]EOL
    */
    $line = explode(':::', $line);

    // Sanity
    if (count($line) !== 6) {
        unset($pmaLogs[$key]);
        $updatePmaLogFile = true;
        continue;
    }

    $log = new PMA_logEntry();
    $log->timestamp = $line[0];
    $log->level = $line[2];
    $log->ip = $line[3];
    $log->text = $line[4];

    if ($cleanLogs) {
        // Remove too old logs
        if (time() > ($keepLogsDuration + $log->timestamp)) {
            unset($pmaLogs[$key]);
            $updatePmaLogFile = true;
            continue;
        }
    }
    $page->logs->addLog($log);
}
/*
* Update log file.
*/
if (isset($updatePmaLogFile)) {
    $pmaLogs = array_reverse($pmaLogs);
    file_put_contents(PMA_FILE_LOGS, $pmaLogs);
}
/*
* Setup stats.
*/
$stats = $page->logs->getStats();
$stat = $stats['total_unfiltred_logs'].' / '.$stats['total_of_logs']. ' logs';
$PMA->infoPanel->addFillOccas($stat);
