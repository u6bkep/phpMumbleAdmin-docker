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

if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
    $PMA->redirection();
}

$page->langs = PMA_optionsHelper::getLanguages($PMA->config->get('default_lang'));
$page->skins = PMA_optionsHelper::getSkins($PMA->config->get('default_skin'));
$page->timezones = PMA_optionsHelper::getTimezones($PMA->config->get('default_timezone'));
$page->timeFormats = PMA_optionsHelper::getTimeFormats($PMA->config->get('default_time'));
$page->dateFormats = PMA_optionsHelper::getDateFormats($PMA->config->get('default_date'));
$page->systemLocales = PMA_optionsHelper::getSystemLocales($PMA->config->get('defaultSystemLocales'));
$page->systemLocalesProfiles = $PMA->config->get('systemLocalesProfiles');

$page->availableSystemLocales = array();
foreach ($page->systemLocales as $array) {
    // Dont show a system locale that already set in a profile
    if (! in_array($array['locale'], $page->systemLocalesProfiles)) {
        $page->availableSystemLocales[] = $array['locale'];
    }
}
