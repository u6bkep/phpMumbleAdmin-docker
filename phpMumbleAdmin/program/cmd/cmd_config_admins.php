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
* require PMA_USER_ADMIN minimum.
*/
if (! $PMA->user->isMinimum(PMA_USER_ADMIN)) {
    throw new PMA_cmdException('illegal_operation');
}

$PMA->admins = new PMA_datas_admins();
$PMA->admins->addCustomLogin($PMA->config->get('SA_login'));

/*
* CMD:
* Change self password.
*/
if (isset($cmd->PARAMS['change_own_pw'])) {

    if (! $PMA->user->isPmaAdmin()) {
        throw new PMA_cmdException('illegal_operation');
    }

    $adm = $PMA->admins->get($PMA->user->adminID);

    // Check current admin password
    if (! PMA_passwordHelper::check($cmd->PARAMS['current'], $adm['pw'])) {
        throw new PMA_cmdException('auth_error');
    }

    if (! PMA_passwordHelper::confirm($cmd->PARAMS['new_pw'], $cmd->PARAMS['confirm_new_pw'])) {
        throw new PMA_cmdException('password_check_failed');
    }

    $adm['pw'] = PMA_passwordHelper::crypt($cmd->PARAMS['new_pw']);

    $PMA->admins->modify($adm);
    $PMA->admins->forceSaveDatasInDB();

    if ($PMA->admins->isLastSaveSuccess()) {
        $PMA->message('change_pw_success');
    } else {
        $PMA->messageError('cmd_process_error');
        $PMA->debugError('Couldn\'t save admins datas in DB.');
    }

/*
* CMD:
* Add new admin.
*/
} elseif (isset($cmd->PARAMS['add_new_admin'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $cmd->PARAMS['class'] = (int)$cmd->PARAMS['class'];

    if (! $PMA->user->isSuperior(($cmd->PARAMS['class']))) {
        throw new PMA_cmdException('illegal_operation');
    }

    $login = $cmd->PARAMS['login'];

    if (! $PMA->admins->validateLoginChars($login)) {
        throw new PMA_cmdException('invalid_username');
    }

    if ($PMA->admins->loginExists($login)) {
        throw new PMA_cmdException('username_exists');
    }

    if (! PMA_passwordHelper::confirm($cmd->PARAMS['new_pw'], $cmd->PARAMS['confirm_new_pw'])) {
        throw new PMA_cmdException('password_check_failed');
    }

    $id = $PMA->admins->add(
        $login,
        $cmd->PARAMS['new_pw'],
        $cmd->PARAMS['email'],
        $cmd->PARAMS['name'],
        $cmd->PARAMS['class']
    );
    $PMA->admins->forceSaveDatasInDB();

    if ($PMA->admins->isLastSaveSuccess()) {
        $cmd->logUserAction('Admin account created ('.$id.'# '.$login.' )');
        $PMA->message('registration_created_success');
    } else {
        $PMA->messageError('cmd_process_error');
        $PMA->debugError('Couldn\'t save admins datas in DB.');
    }

/*
* CMD:
* Delete an admin.
*/
} elseif (isset($cmd->PARAMS['remove_admin'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }

    if (! isset($cmd->PARAMS['confirmed'])) {
        throw new PMA_cmdExitException();
    }

    $id = (int)$cmd->PARAMS['remove_admin'];

    $adm = $PMA->admins->get($id);

    if (is_null($adm) OR ! $PMA->user->isSuperior($adm['class'])) {
        throw new PMA_cmdException('illegal_operation');
    }

    $PMA->admins->delete($id);
    $PMA->admins->forceSaveDatasInDB();

    if ($PMA->admins->isLastSaveSuccess()) {
        $cmd->logUserAction('Admin account deleted ('.$id.'# '.$adm['login'].' )');
        $PMA->message('registration_deleted_success');
    } else {
        $PMA->messageError('cmd_process_error');
        $PMA->debugError('Couldn\'t saved admins datas in DB.');
    }

/*
* CMD:
* Edit admin registration.
*/
} elseif (isset($cmd->PARAMS['edit_registration'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $id = (int)$cmd->PARAMS['edit_registration'];

    $adm = $original = $PMA->admins->get($id);

    if (is_null($adm) OR ! $PMA->user->isSuperior($adm['class'])) {
        throw new PMA_cmdException('illegal_operation');
    }

    // Login
    if ($cmd->PARAMS['login'] !== $adm['login']) {

        if (! $PMA->admins->validateLoginChars($cmd->PARAMS['login'])) {
            throw new PMA_cmdException('invalid_username');
        }

        if ($PMA->admins->loginExists($cmd->PARAMS['login'])) {
            throw new PMA_cmdException('username_exists');
        }

        $adm['login'] = $cmd->PARAMS['login'];
    }

    // Password
    if ($cmd->PARAMS['new_pw'] !== '') {
        if (! PMA_passwordHelper::confirm($cmd->PARAMS['new_pw'], $cmd->PARAMS['confirm_new_pw'])) {
            throw new PMA_cmdException('password_check_failed');
        }
        $adm['pw'] = PMA_passwordHelper::crypt($cmd->PARAMS['new_pw']);
    }

    // Class
    $class = (int)$cmd->PARAMS['class'];
    /*
    * Check if the new class is authorized.
    */
    if ($PMA->user->isSuperior($class)) {
        $adm['class'] = $class;
    }

    // Email
    $adm['email'] = $cmd->PARAMS['email'];
    // Name
    $adm['name'] = $cmd->PARAMS['name'];

    // Check if the registration has been modified
    $diff = arrayDiffStrict($adm, $original);

    if (! empty($diff)) {

        $PMA->admins->modify($adm);
        $PMA->admins->forceSaveDatasInDB();

        if ($PMA->admins->isLastSaveSuccess()) {
            if (isset($diff['login'])) {
                $cmd->logUserAction('Admin login updated ('.$adm['id'].'# '.$original['login'].' => '.$adm['login'].' )');
            }
            if (isset($diff['pw'])) {
                $PMA->message('change_pw_success');
                $cmd->logUserAction('Admin password updated ('.$adm['id'].'# '.$adm['login'].' )');
            }
            if (isset($diff['class'])) {
                $className = pmaGetClassName($adm['class']);
                $cmd->logUserAction('Admin class updated ('.$adm['id'].'# '.$adm['login'].' => '.$className.' )');
            }
        } else {
            $PMA->messageError('cmd_process_error');
            $PMA->debugError('Couldn\'t saved admins datas in DB.');
        }
    }

/*
* CMD:
* Edit admin access.
*/
} elseif (isset($cmd->PARAMS['editAccess'])) {

    $cmd->setRedirection('referer');

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $id = (int)$cmd->PARAMS['editAccess'];

    $adm = $PMA->admins->get($id);

    if (is_null($adm) OR ! $PMA->user->isSuperior($adm['class'])) {
        throw new PMA_cmdException('illegal_operation');
    }

    $profile_id = $PMA->router->getRoute('profile');

    // Full Access
    if (isset($cmd->PARAMS['fullAccess'])) {
        $adm['access'][$profile_id] = '*';
    } else {

        $access = '';

        foreach ($cmd->PARAMS as $key => $value) {
            /*
            * Memo:
            * A digital key in POST return an integer
            * example $cmd->PARAMS[0], $cmd->PARAMS[1]
            */
            if (is_int($key) && $value === 'on') {
                $access .= $key.';';
            }
        }

        if ($access !== '') {
            $adm['access'][$profile_id] = substr($access, 0, -1);
        } else {
            unset($adm['access'][$profile_id]);
        }
    }

    $PMA->admins->modify($adm);
    $PMA->admins->forceSaveDatasInDB();

    if ($PMA->admins->isLastSaveSuccess()) {
        $cmd->logUserAction('Admin access updated ('.$adm['id'].'# '.$adm['login'].' )');
    } else {
        $PMA->messageError('cmd_process_error');
        $PMA->debugError('Couldn\'t saved admins datas in DB.');
    }

/*
* CMD:
* Edit SuperAdmin.
*/
} elseif (isset($cmd->PARAMS['edit_SuperAdmin'])) {

    if (! $PMA->user->is(PMA_USER_SUPERADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $cmd->setRedirection('referer');
    /*
    * Check current SuperAdmin password.
    */
    if (! PMA_passwordHelper::check($cmd->PARAMS['current'], $PMA->config->get('SA_pw'))) {
        throw new PMA_cmdException('auth_error');
    }
    /*
    * SuperAdmin login
    */
    $updateLogin = false;

    if ($cmd->PARAMS['login'] !== $PMA->config->get('SA_login')) {
        if (! $PMA->admins->validateLoginChars($cmd->PARAMS['login'])) {
            throw new PMA_cmdException('invalid_username');
        }
        if ($PMA->admins->loginExists($cmd->PARAMS['login'])) {
            throw new PMA_cmdException('username_exists');
        }
        $PMA->config->set('SA_login', $cmd->PARAMS['login']);
        $updateLogin = true;
    }
    /*
    * SuperAdmin password
    */
    $updatePw = false;

    if ($cmd->PARAMS['new_pw'] !== '') {
        if (! PMA_passwordHelper::confirm($cmd->PARAMS['new_pw'], $cmd->PARAMS['confirm_new_pw'])) {
            throw new PMA_cmdException('password_check_failed');
        }
        $PMA->config->set('SA_pw', PMA_passwordHelper::crypt($cmd->PARAMS['new_pw']));
        $updatePw = true;
    }

    if ($updateLogin OR $updatePw) {

        $PMA->config->forceSaveDatasInDB();

        if ($PMA->config->isLastSaveSuccess()) {
            if ($updateLogin) {
                $PMA->user->setLogin($cmd->PARAMS['login']);
                $cmd->logUserAction('SuperAdmin login updated');
            }
            if ($updatePw) {
                $cmd->logUserAction('SuperAdmin password updated');
                $PMA->message('change_pw_success');
            }
        } else {
            $PMA->messageError('cmd_process_error');
            $PMA->debugError('Couldn\'t saved admins datas in DB.');
        }
    }
}
