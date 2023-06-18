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

$PMA->popups->newHidden('adminsRegistrationEditor');

/*
* Admin registration.
*/
$page->set('admID', $registration['id']);
$page->admClass = $registration['class'];
$page->set('admClassName', pmaGetClassName($registration['class']));
$page->set('admLogin', $registration['login']);
$page->set('admCreatedUptime', PMA_datesHelper::uptime(time() - $registration['created']));
$page->set('admCreatedDate', $DATES->strDateTime($registration['created']));
$page->set('admName', $registration['name']);
if ($registration['last_conn'] > 0) {
    $page->lastConn = true;
    $page->set('lastConnUptime', PMA_datesHelper::uptime(time() - $registration['last_conn']));
    $page->set('lastConnDate', $DATES->strDateTime($registration['last_conn']));
}
if ($registration['email'] !== '') {
    $page->email = true;
    $page->set('email', $registration['email']);
}
/*
* Admin access.
*/
$page->set('hasFullAccess', false);
$page->profilesAccess = array();
$page->serversScroll = array();
/*
* Profiles access.
*/
foreach ($registration['access'] as $profileID => $servers) {

    $nameEnc = htEnc($PMA->profiles->getName($profileID));

    if (hasFullAccess($servers)) {
        $text = sprintf(''.$TEXT['full_access'], $nameEnc);
    } else {
        $text = sprintf($TEXT['srv_access'], $nameEnc, count(strToArrayAccess($servers)));
    }

    $data = new stdClass();
    $data->selected = ($profileID === $PMA->router->getRoute('profile'));
    $data->textEnc = $text;

    $page->profilesAccess[] = $data;
}
/*
* Current profile access.
*/
if ($registration['class'] > PMA_USER_ROOTADMIN) {

    $page->showServersScroll = true;

    $pid = $PMA->router->getRoute('profile');

    $profileAccess = array();
    if (isset($registration['access'][$pid])) {
        $page->set('hasFullAccess', (hasFullAccess($registration['access'][$pid])));
        $profileAccess = strToArrayAccess($registration['access'][$pid]);
    }

    $cache = PMA_serversCacheHelper::get('normal');

    if (isset($cache['vservers'])) {
        foreach ($cache['vservers'] as $array) {
            $data = new stdClass();
            $data->id = $array['id'];
            $data->label = 's'.$data->id;
            $data->name = $array['name'];
            $data->chked = hasAccessToServer($data->id, $profileAccess);
            $page->serversScroll[] = $data;
        }
    }
}
