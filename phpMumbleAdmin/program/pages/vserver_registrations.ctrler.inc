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
* Registrations require a booted server.
*/
if (! $page->vserverIsBooted) {
    throw new PMA_pageException('vserver_offline_info');
}
/*
* Load mumble images definitions.
*/
require PMA_DIR_INCLUDES.'def.images.mumble.inc';
/*
* Load texts.
*/
pmaLoadLanguage('vserver_registrations');
/*
* Get all registrations.
*/
$page->registredUsers = $prx->getRegisteredUsers('');
/*
* Remove SuperUser registration for SuperUser_ru.
*/
if ($PMA->user->is(PMA_USER_SUPERUSER_RU)) {
    unset($page->registredUsers[0]);
}
/*
* Add occasional panel fill.
*/
if ($page->showInfoPanel && $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
    $total = count($page->registredUsers);
    $text = sprintf($TEXT['fill_registrations'], '<mark>'.$total.'</mark>');
    $PMA->infoPanel->addFillOccas($text);
}
/*
* Setup user status.
*/
$page->usersStatus = new PMA_ctrler_usersStatus();
$page->usersStatus->setOnlineList($page->onlineUsersList);
/*
* Setup navigation.
*/
if ($PMA->user->is(PMA_USER_MUMBLE)) {
    // Mumble users *MUST* get their own registration
    $MiscNav = $PMA->user->mumbleUID;
} else {
    $MiscNav = $PMA->router->getMiscNavigation('mumbleRegistration');
}
/*
* Setup single registration.
*/
if (is_int($MiscNav)) {
    /*
    * Invalid registration ID.
    */
    if (! isset($page->registredUsers[$MiscNav])) {
        if ($PMA->user->is(PMA_USER_MUMBLE)) {
            $PMA->logout();
        } else {
            $PMA->router->removeMisc('mumbleRegistration');
        }
        $PMA->messageError('Murmur_InvalidUserException');
        $PMA->redirection();
    }
    /*
    * Setup registration.
    */
    $registration = $prx->getRegistration($MiscNav);
    $registration = new PMA_ctrler_mumbleRegistrationObject($registration);
    $registration->id = $MiscNav;
    $registration->isOwnAccount = ($PMA->user->mumbleUID === $registration->id);
    if ($registration->isOwnAccount) {
        pmaLoadLanguage('vserver_registrations_own');
    }
}
