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
* User Class Sanity.
*
* Check for user class,
* require PMA_USER_ROOTADMIN minimum.
*/
if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
    throw new PMA_cmdException('illegal_operation');
}

/*
* CMD:
* Add an Ice profile
*/
if (isset($cmd->PARAMS['add_profile'])) {

    $name = $cmd->PARAMS['add_profile'];

    if ($name === '') {
        throw new PMA_cmdException('empty_profile_name');
    }

    $id = $PMA->profiles->add($name);
    $cmd->logUserAction('profile created ('.$id.'# '.$name.' )');
    $PMA->router->profile->setCurrentRoute($id);

/*
* CMD:
* Delete current Ice profile.
*/
} elseif (isset($cmd->PARAMS['delete_profile'])) {

    if (! isset($cmd->PARAMS['confirmed'])) {
        throw new PMA_cmdExitException();
    }

    $id = $PMA->router->getRoute('profile');

    $profile = $PMA->userProfile;

    $PMA->profiles->delete($id);

    $cmd->logUserAction('profile deleted ('.$id.'# '.$profile['name'].' )');

    $PMA->admins = new PMA_datas_admins();
    $PMA->admins->deleteProfileAccess($id);

    // Set profile_id to a valid profile id
    if ($id === $PMA->config->get('default_profile')) {
        $first = $PMA->profiles->getFirst();
        $newProfile = $first['id'];
    } else {
        $newProfile = $PMA->config->get('default_profile');
    }
    $PMA->router->profile->setCurrentRoute($newProfile);

/*
* CMD:
* Set current Ice profile as default profile.
*/
} elseif (isset($cmd->PARAMS['set_default_profile'])) {

    $id = $PMA->router->getRoute('profile');
    $name = $PMA->profiles->getName($id);
    $PMA->config->set('default_profile', $id);
    $cmd->logUserAction('Default profile modified ('.$id.'# '.$name.' )');

/*
* CMD:
* Edit current Ice profile.
*/
} elseif (isset($cmd->PARAMS['edit_profile'])) {

    if (is_null($PMA->userProfile)) {
        throw new PMA_cmdException('invalid_Ice_profile');
    }

    $profile = $original = $PMA->userProfile;

    // Name
    if ($cmd->PARAMS['name'] !== '') {
        $profile['name'] = $cmd->PARAMS['name'];
    }

    // Toggle public
    $profile['public'] = isset($cmd->PARAMS['public']);

    // Host
    // An empty or digit host return an ice exection, deny it.
    if ($cmd->PARAMS['host'] !== '' && ! ctype_digit($cmd->PARAMS['host'])) {
        $profile['host'] = $cmd->PARAMS['host'];
        unset($_SESSION['page_vserver']);
    }

    // Port
    if (checkPort($cmd->PARAMS['port'])) {
        $profile['port'] = (int)$cmd->PARAMS['port'];
        unset($_SESSION['page_vserver']);
    } else {
        $PMA->messageError('invalid_port');
    }

    // Timeout
    $timeout = $cmd->PARAMS['timeout'];

    if (ctype_digit($timeout) && $timeout > 0) {
        $profile['timeout'] = (int)$timeout;
    } else {
        $PMA->messageError(array('invalid_numerical', 'timeout > 0'));
    }

    // Secret
    $profile['secret'] = $cmd->PARAMS['secret'];

    // PHP-slice
    if (isset($cmd->PARAMS['slice_php'])) {
        $profile['slice_php'] = $cmd->PARAMS['slice_php'];
    }

    // HTTP address
    $profile['http-addr'] = $cmd->PARAMS['http_addr'];

    // Check if the profile has been modified
    $diff = arrayDiffStrict($profile, $original);

    if (! empty($diff)) {
        $PMA->profiles->modify($profile);
        $cmd->logUserAction('profile updated ('.$profile['id'].'# '.$profile['name'].' )');
    }
}


