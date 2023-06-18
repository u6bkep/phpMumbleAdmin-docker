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
* require PMA_USER_MUMBLE minimum.
*/
if (! $PMA->user->isMinimum(PMA_USER_MUMBLE)) {
    throw new PMA_cmdException('illegal_operation');
}

/*
* Initiate Murmur connection.
*/
require PMA_DIR_INCLUDES.'iceConnection.inc';
if (! $PMA->murmurMeta->isConnected()) {
    throw new PMA_cmdException();
}

/*
* Get vserver proxy.
*/
if (is_null($prx = $PMA->murmurMeta->getServer($_SESSION['page_vserver']['id']))) {
    throw new PMA_cmdException('Murmur_InvalidServerException');
}

/*
* Mumble users must get their own registration
*/
if ($PMA->user->is(PMA_USER_MUMBLE)) {
    $uid = $PMA->user->mumbleUID;
} else {
    $uid = $PMA->router->getMiscNavigation('mumbleRegistration');
}

if (is_int($uid)) {
    /*
    * SuperUser_ru can't have access to SuperUser account
    */
    if ($uid === 0 && $PMA->user->is(PMA_USER_SUPERUSER_RU)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $registration = $prx->getRegistration($uid);

    $selfRegistration = ($uid === $PMA->user->mumbleUID);
}

/*
* CMD:
* Add new account.
*/
if (isset($cmd->PARAMS['add_new_account'])) {

    $login = $cmd->PARAMS['add_new_account'];

    if (! $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
        throw new PMA_cmdException('illegal_operation');
    }

    /*
    * Memo : registerUser() return the uid of the new account.
    * Memo : registerUser() verify for invalid characters but
    * updateRegistration() not.
    */
    if (! $prx->validateUserChars($login)) {
        throw new PMA_cmdException('invalid_username');
    }

    try {
        $new = $prx->registerUser(array($login));
    } catch (Murmur_InvalidUserException $e) {
        throw new PMA_cmdException('username_exists');
    }

    $PMA->message('registration_created_success');

    if (isset($cmd->PARAMS['redirect_to_new_account'])) {
        $cmd->setRedirection('?mumbleRegistration='.$new);
    }

/*
* CMD:
* Delete an account with it's ID.
*/
} elseif (isset($cmd->PARAMS['delete_account_id'])) {

    if (! isset($cmd->PARAMS['confirmed'])) {
        throw new PMA_cmdExitException();
    }

    if (! $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $id = $cmd->PARAMS['delete_account_id'];

    if (! ctype_digit($id)) {
        throw new PMA_cmdException('invalid_numerical');
    }

    $id = (int)$id;

    if ($id > 0) {
        $prx->unregisterUser($id);
        $PMA->message('registration_deleted_success');
    } else {
        throw new PMA_cmdException('illegal_operation');
    }

/*
* CMD:
* Delete current account.
*/
} elseif (isset($cmd->PARAMS['delete_account'])) {

    if (! isset($cmd->PARAMS['confirmed'])) {
        throw new PMA_cmdExitException();
    }

    /*
    * Check if a registered user have the right to delete his account.
    */
    if ($PMA->user->is(PMA_USER_MUMBLE) && ! $PMA->config->get('RU_delete_account')) {
        throw new PMA_cmdException('illegal_operation');
    }

    if ($uid > 0) {

        $prx->unregisterUser($uid);

        if ($selfRegistration) {
            $PMA->logout();
        } else {
            $PMA->router->removeMisc('mumbleRegistration');
        }

        $PMA->message('registration_deleted_success');

    } else {
        throw new PMA_cmdException('illegal_operation');
    }

/*
* CMD:
* Edit registration.
*/
} elseif (isset($cmd->PARAMS['editRegistration'])) {

    // Setup login
    if (! isset($registration[0])) {
        $registration[0] = '';
    }
    // Setup email
    if (! isset($registration[1])) {
        $registration[1] = '';
    }
    // Setup comment
    if (! isset($registration[2])) {
        $registration[2] = '';
    }

    $original = $registration;

    $allowModifyLogin = (
        $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU) OR
        $PMA->config->get('RU_edit_login')
    );
    $allowModifyPw = (
        $PMA->user->isMinimum(PMA_USER_ADMIN) OR
        $PMA->config->get('SU_edit_user_pw') OR
        $selfRegistration
    );

    /*
    * Modify login
    */
    if ($allowModifyLogin) {
        if ($cmd->PARAMS['login'] !== '' && $cmd->PARAMS['login'] !== $registration[0]) {
            if ($prx->validateUserChars($cmd->PARAMS['login'])) {
                $registration[0] = $cmd->PARAMS['login'];
            } else {
                $PMA->messageError('invalid_username');
            }
        }
    }
    /*
    * Modify email
    */
    if ($cmd->PARAMS['email'] !== $registration[1]) {
        $registration[1] = $cmd->PARAMS['email'];
    }
    /*
    * Modify comment
    */
    if ($cmd->PARAMS['comm'] !== $registration[2]) {
        $registration[2] = $prx->removeHtmlTags($cmd->PARAMS['comm'], $stripped);
        if ($stripped) {
            $PMA->messageError('vserver_dont_allow_HTML');
        }
    }
    /*
    * Modify password
    */
    if ($allowModifyPw && $cmd->PARAMS['new_pw'] !== '') {
        /*
        * Verify password if user edit self account.
        */
        if ($selfRegistration) {
            if (! isset($cmd->PARAMS['current'])) {
                throw new PMA_cmdException('illegal_operation');
            }
            /*
            * Memo:
            * verifyPassword() return user ID on successfull authentification,
            * else -1 for bad password and -2 for unknown username.
            */
            $auth = $prx->verifyPassword($PMA->user->login, $cmd->PARAMS['current']);

            if ($auth !== $PMA->user->mumbleUID) {
                throw new PMA_cmdException('auth_error');
            }
        }

        if ($cmd->PARAMS['new_pw'] !== $cmd->PARAMS['confirm_new_pw']) {
            $PMA->messageError('password_check_failed');
        }

        $registration[4] = $cmd->PARAMS['new_pw'];
    }

    $diff = arraydiffstrict($registration, $original);

    if (! empty($diff)) {
        try {
            $prx->updateRegistration($uid, $registration);
        } catch (Murmur_InvalidUserException $e) {
            throw new PMA_cmdException('username_exists');
        }
        if (isset($diff[0])) {
            // Update PMA user login name on success
            if ($selfRegistration) {
                $PMA->user->setLogin($diff[0]);
            }
        }
       if (isset($diff[4])) {
            // Verify that's the password has changed:
            $verifyPassword = $prx->verifyPassword($registration[0], $diff[4]);
            if ($verifyPassword === $uid) {
                $PMA->message('change_pw_success');
            } else {
                $PMA->messageError('change_pw_error');
            }
        }
    }

/*
* CMD:
* Remove registration avatar.
*/
} elseif (isset($cmd->PARAMS['remove_avatar'])) {

    if (isset($cmd->PARAMS['confirmed'])) {
        $prx->setTexture($uid, array());
    }

/*
* CMD:
* Registration Search.
*/
} elseif (isset($cmd->PARAMS['registrations_search'])) {

    if (! $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $search = $cmd->PARAMS['registrations_search'];

    if ($search === '') {
        unset($_SESSION['search']['registrations']);
    } else {
        $_SESSION['search']['registrations'] = $search;
    }

/*
* CMD:
* Reset search.
*/
} elseif (isset($cmd->PARAMS['reset_registrations_search'])) {
    if (! $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
        throw new PMA_cmdException('illegal_operation');
    }
    unset($_SESSION['search']['registrations']);
}
