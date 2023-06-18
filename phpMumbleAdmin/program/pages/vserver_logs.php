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
* Load logs language.
* Setup log widget.
*/
pmaLoadLanguage('logs');
$PMA->widgets->newModule('logs');
/*
* Get murmur logs.
*/
$maxLogsSize = $PMA->config->get('vlogs_size');
$start = microTime();
try {
    $getLogs = $prx->getLog(0, $maxLogsSize);
} catch (Ice_MemoryLimitException $e) {
    $PMA->messageError('Ice_MemoryLimitException_logs');
    $maxLogsSize = 100;
    $getLogs = $prx->getLog(0, $maxLogsSize);
}
$PMA->debug('getLog duration '.PMA_statsHelper::duration($start), 3);
/*
 * Get murmur logs lenght.
 *
 * Bug:
 * getLogLen require icesecretwrite with murmur 1.2.3,
 * it's fixed with murmur 1.2.4.
 *
 * Workaround:
 * Just catch the exception to avoid a fatal error, and do nothing.
 */
try {
    $getLogsLen = $prx->getLogLen();
} catch (Murmur_InvalidSecretException $e) {
    // Just do nothing.
}
/*
* Setup logs controller.
*/
$page->logs = new PMA_logsMumble();
$page->logs->setAllowReplacement($PMA->cookie->get('replace_logs_str'));
$page->logs->setAllowHighlight($PMA->cookie->get('highlight_logs'));
if (isset($_SESSION['search']['logs'])) {
    $page->logs->setSearchPattern($_SESSION['search']['logs']);
}
/*
* Never let SuperUsers highlight logs if it's not authorised.
*/
if (! $PMA->config->get('vlogs_admins_highlights') && $PMA->user->is(PMA_USERS_LOWADMINS)) {
    $page->logs->setAllowHighlight(false);
}
/*
* Add logs text replacement rules.
*/
if ($page->logs->isAllowReplacement()) {
    $longString = 'Connection closed: The remote host closed the connection [1]';
    $page->logs->addReplacementRule('str', $longString, 'has left the server');
    $page->logs->addReplacementRule('reg_ex', '/^Stopped$/', 'Server stopped');
}
/*
* Add logs text filters rules.
*/
require PMA_DIR_INCLUDES.'murmurLogsFiltersRules.inc'; // Get $logsfilters
foreach ($logsfilters as $array) {
    // Dont add "has left the server" if replacement is not enable
    if ($array['mask'] === 1024 && ! $page->logs->isAllowReplacement()) {
        continue;
    }
    $page->logs->addFilterRule(
        $array['mask'],
        $array['pattern'],
        (bool) ($array['mask'] & $PMA->cookie->get('logsFilters'))
    );
}
/*
* Add logs text highlights rules
*/
if ($page->logs->isAllowHighlight()) {
    if ($page->logs->isAllowReplacement()) {
        $page->logs->addHighlightRule('Lclosed', 'has left the server');
        $page->logs->addHighlightRule('Lerror', 'Server stopped');
    } else {
        $page->logs->addHighlightRule('Lclosed', 'Connection closed: The remote host closed the connection [1]');
    }
    $page->logs->addHighlightRule('Lwarn', 'Ignoring connection:');
    $page->logs->addHighlightRule('Lauth', 'Authenticated');
    $page->logs->addHighlightRule('Lauth', 'New connection:');
    $page->logs->addHighlightRule('Lauth', 'Connection closed');
    if ($page->logs->isAllowReplacement()) {
        $page->logs->addHighlightRule('Linfo', 'Moved to channel');
        $page->logs->addHighlightRule('Ladmin', 'Moved user');
    }
    $page->logs->addHighlightRule('Lwarn', 'not allowed to');
    $page->logs->addHighlightRule('Lerror', 'SSL Error:');
    // Memo: This rule must be before "Moved channel" to avoid a bug
    $page->logs->addHighlightRule('Ladmin', 'Removed channel');
    $page->logs->addHighlightRule('Ladmin', 'Moved channel');
    $page->logs->addHighlightRule('Ladmin', 'Changed speak-state');
    $page->logs->addHighlightRule('Ladmin', 'Added channel');
    $page->logs->addHighlightRule('Ladmin', 'Renamed channel');
    $page->logs->addHighlightRule('Ladmin', 'Updated ACL');
    $page->logs->addHighlightRule('Ladmin', 'Updated banlist');
    $page->logs->addHighlightRule('Lwarn', 'Server is full');
    $page->logs->addHighlightRule('Lwarn', 'Rejected connection');
    $page->logs->addHighlightRule('Linfo', 'Disconnecting ghost');
    $page->logs->addHighlightRule('Linfo', 'Certificate hash is banned.');
    $page->logs->addHighlightRule('Lwarn', '(Server ban)');
    $page->logs->addHighlightRule('Lwarn', '(Global ban)');
    $page->logs->addHighlightRule('Lwarn', 'Timeout');
    $page->logs->addHighlightRule('Lwarn', 'Generating new server certificate.');
    $page->logs->addHighlightRule('Lerror', 'The address is not available');
    $page->logs->addHighlightRule('Lerror', 'The bound address is already in use');
    // Memo: This rule must be before "Announcing server via bonjour" to avoid a bug
    $page->logs->addHighlightRule('Lwarn', 'Stopped announcing server via bonjour');
    $page->logs->addHighlightRule('Lwarn', 'Announcing server via bonjour');
    $page->logs->addHighlightRule('Linfo', 'Server listening on');
    $page->logs->addHighlightRule('Lwarn', 'Binding to address');
    $page->logs->addHighlightRule('Ladmin', 'Unregistered user');
    $page->logs->addHighlightRule('Ladmin', 'Renamed user');
    $page->logs->addHighlightRule('Ladmin', 'Kicked');
    $page->logs->addHighlightRule('Ladmin', 'Kickbanned');
}
/*
* Control each logs.
*/
foreach ($getLogs as $murmurLog) {
    $log = new PMA_logEntry();
    $log->text = $murmurLog->txt;
    $log->timestamp = $murmurLog->timestamp;
    $page->logs->addLog($log);
}
/*
* Stats
*/
$stats = $page->logs->getStats();

if (isset($getLogsLen) && $getLogsLen > $maxLogsSize) {
    $totalLogs = $maxLogsSize.' / '.$getLogsLen;
} else {
    $totalLogs = $stats['total_of_logs'];
}

if ($page->showInfoPanel) {
    $logsStat = sprintf($TEXT['fill_logs'], '<mark>'.$totalLogs.'</mark>');
    $PMA->infoPanel->addFillOccas($logsStat);
}
/*
* Setup filters menu.
*/
$filtersMenu = new filtersMenu();
$filtersMenu->setImgActive(PMA_IMG_OK_16);
$filtersMenu->setImgInactive(PMA_IMG_CANCEL_16);
// Allow replacement link
$filtersMenu->addFilterLink(
    'replace_logs_str',
    $TEXT['replace_logs_str'],
    $page->logs->isAllowReplacement()
);
// Highlight link
if ($PMA->user->isMinimum(PMA_USER_ROOTADMIN) OR $PMA->config->get('vlogs_admins_highlights')) {
    $filtersMenu->addFilterLink(
        'toggle_highlight',
        $TEXT['highlight_logs'],
        $page->logs->isAllowHighlight()
    );
}
// Separation
$filtersMenu->addSeparation();
// Logs filters.
foreach ($page->logs->getFiltersRules() as $filter) {
    $css = $filter->active ? 'filtered' : 'unfiltered';
    $count = '(<span class="'.$css.'">'.$stats['filter'.$filter->mask].'</span>)';
    $txt = $filter->pattern.' '.$count;
    $filtersMenu->addFilterLink(
        'toggle_log_filter='.$filter->mask,
        $txt,
        $filter->active
    );
}
// Separation
$filtersMenu->addSeparation();
// Stats
$filtersMenu->addText($TEXT['log_filtered'].' : <span class="count">'.$stats['total_filtered_logs'].'</span>/'.$stats['total_possible_filter_logs']);

/*
* Setup search widget
*/
$PMA->widgets->newModule('search');

$search = new PMA_search();
$search->setCMDroute('murmur_logs');
$search->setCMDname('logs_search');
if (isset($_SESSION['search']['logs'])) {
    $search->setSearchValue($_SESSION['search']['logs']);
    $search->setTotalFound($stats['total_search_found']);
    $search->setRemoveSearchHREF('?cmd=murmur_logs&amp;reset_logs_search');
}

$PMA->widgets->saveDatas('search', $search);

