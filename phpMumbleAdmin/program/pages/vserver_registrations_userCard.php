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

$page->set('login', $registration->name);
$page->set('email', $registration->email);
$page->certificat = $registration->cert;
$page->showCertificatHash = $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU);
$page->showAvatar = (
    $PMA->user->isMinimum(PMA_USER_ROOTADMIN) OR ! $PMA->config->get('show_avatar_sa')
);
$page->deleteAccount = (
    $registration->id > 0 &&
    ($PMA->user->isMinimum(PMA_USER_SUPERUSER_RU) OR $PMA->config->get('RU_delete_account'))
);

PMA_sandBoxHelper::create($registration->desc);
/*
* Setup regitered user status.
*/
$status = $page->usersStatus->check($registration->id);
$page->userIsOnline = $status->isOnline;
$page->userIsOnlineLink = false;
if ($page->userIsOnline && $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
    $page->userIsOnlineLink = true;
    $page->set('statusUrl', $status->sessionUrl);
}
/*
* Setup last activity.
*/
if (is_int($registration->lastActivity)) {
    $ts = PMA_datesHelper::datetimeToTimestamp($registration->lastActivity);
    $page->set('lastActivity', PMA_datesHelper::uptime(time() - $ts));
    $page->set('lastActivityTitle', $DATES->strDateTime($ts));
} else {
    $page->set('lastActivity', '');
    $page->set('lastActivityTitle', '');
}
/*
* Check PHP memory limit (>32M).
* getTexture return huge array with big avatars images (almost 20M for an avatar of 128k, 60M for 450K).
* A minimum of 32M memory limit is required, 64M to avoid any kind of php fatal error on memory limit.
* MEMO:
* Mumble refuse avatar superior than 500K, even with "imagemessagelength" > 1310720 (1.28M).
*/
if ($page->showAvatar) {
    $srvLimit = (int)$prx->getParameter('imagemessagelength');
    $phpLimit = getIntegerMemoryLimit();
    if ($srvLimit <= 131072) {
        if ($phpLimit < (32*1000*1024)) {
            $page->showAvatar = false;
            $PMA->debugError('Php memory limit is too low (<32M). Showing avatar has been disabled.');
        }
    } else {
        if ($phpLimit < (64*1000*1024)) {
            $page->showAvatar = false;
            $PMA->debugError('Php memory limit is too low (<64M). Showing avatar has been disabled.');
        }
    }
}
/*
* Construct avatar.
*/
if ($page->showAvatar) {
    $start = microTime();
    $texture = $prx->getTexture($registration->id);
    $PMA->debug('getTexture duration '.PMA_statsHelper::duration($start), 3);

    $page->avatar = new PMA_MumbleAvatar($texture);
    $page->avatar->setProfile_id($PMA->cookie->get('profile_id'));
    $page->avatar->setServer_id($page->vserverID);
    $page->avatar->setUser_id($registration->id);

    $start = microTime();
    $page->avatar->constructSRC();
    $PMA->debug('constructSRC duration '.PMA_statsHelper::duration($start), 3);
}
/*
* Setup avatar delete link
*/
$page->deleteAvatar = ($page->showAvatar && ! $page->avatar->isEmpty());
/*
* Setup popups.
*/
$PMA->popups->newHidden('mumbleRegistrationEditor');
if ($page->deleteAvatar) {
    $PMA->popups->newHidden('mumbleRegistrationDeleteAvatar');
}
if ($page->deleteAccount) {
    $PMA->popups->newHidden('mumbleRegistrationDelete');
}
