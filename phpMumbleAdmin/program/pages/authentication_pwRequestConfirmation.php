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

if (! $page->allowPasswordRequests) {
    $PMA->messageError('illegal_operation');
    throw new PMA_pageException();
}

$page->message = $TEXT['request_dont_exists'];
$page->newPassword = '';

if (isset($PMA->pwRequests->requestFound)) {
    $request = $PMA->pwRequests->requestFound;
    // Remove
    $PMA->pwRequests->delete($request['id']);
    // Profile host / port must be the same.
    $profile = $PMA->userProfile;
    if ($request['profile_host'] !== $profile['host'] OR $request['profile_port'] !== $profile['port']) {
        $request['sid'] = null;
        $page->message = 'Error during process. Please retry with a new request.';
    }

    if (! is_null($prx = $PMA->murmurMeta->getServer($request['sid']))) {
        // Generate a new random password
        $page->newPassword = genRandomChars(16);
        // Start the virtual server if it's stopped
        $isRunning = $prx->isRunning();
        if (! $isRunning) {
            $prx->start();
        }
        // get user registration
        $user = $prx->getRegistration($request['uid']);
        // set the new generated password
        $user[4] = $page->newPassword;
        // update registration
        $prx->updateRegistration($request['uid'], $user);
        // New password
        $page->message = $TEXT['new_generated_pw'];
        // Stop the virtual server if it was stopped.
        if (! $isRunning) {
            $prx->stop();
        }
    }
}
