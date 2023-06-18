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

$logOptions = array();
for ($i = 100; $i <= 900; $i += 100) {
    $logOptions[] = $i;
}
for ($i = 1000; $i <= 9500; $i += 500) {
    $logOptions[] = $i;
}
for ($i = 10*1000; $i <= 100*1000; $i += 5000) {
    $logOptions[] = $i;
}

$page->logOptions = array();
foreach ($logOptions as $i) {
    $opt = new stdClass();
    $opt->val = $i;
    $opt->select = ($i === $PMA->config->get('vlogs_size'));
    $opt->format = number_format($i);
    $page->logOptions[] = $opt;
}

$page->set('vlogsAdmins', $PMA->config->get('vlogs_admins_active'));
$page->set('vlogsAdminsHighlights', $PMA->config->get('vlogs_admins_highlights'));
$page->set('pmaLogsKeep', $PMA->config->get('pmaLogs_keep'));
$page->set('pmaLogsSaActions', $PMA->config->get('pmaLogs_SA_actions'));
