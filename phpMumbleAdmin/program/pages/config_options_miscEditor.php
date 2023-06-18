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

$page->editDefaultOptions = $PMA->user->isMinimum(PMA_USER_ROOTADMIN);
$page->editSuperAdmin = $PMA->user->is(PMA_USER_SUPERADMIN);
$page->editAdminPassword = $PMA->user->isPmaAdmin();

if ($page->editSuperAdmin) {
    $PMA->popups->newHidden('optionsSuperAdminEditor');
}
if ($page->editAdminPassword) {
    $PMA->popups->newHidden('optionsPasswordEditor');
}

$page->langs = PMA_optionsHelper::getLanguages($PMA->cookie->get('lang'));
$page->skins = PMA_optionsHelper::getSkins($PMA->cookie->get('skin'));
$page->timezones = PMA_optionsHelper::getTimezones($PMA->cookie->get('timezone'));
$page->timeFormats = PMA_optionsHelper::getTimeFormats($PMA->cookie->get('time'));
$page->dateFormats = PMA_optionsHelper::getDateFormats($PMA->cookie->get('date'));
$page->systemLocalesProfiles = PMA_optionsHelper::getSystemLocalesProfiles(
    $PMA->config->get('systemLocalesProfiles'),
    $PMA->cookie->get('installed_localeFormat')
);
$page->uptimeOptions = array();
for ($i = 1; $i <= 3; ++$i) {
    $opt = new stdClass();
    $opt->val = $i;
    $opt->uptime = PMA_datesHelper::uptime(21686399, $i); // 250 jours 59m59s
    $opt->select = ($i === $PMA->cookie->get('uptime'));
    $page->uptimeOptions[] = $opt;
}
$page->set('vserversLogin', $PMA->cookie->get('vserver_login'));
