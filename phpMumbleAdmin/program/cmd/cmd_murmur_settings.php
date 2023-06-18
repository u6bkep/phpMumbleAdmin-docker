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
* require PMA_USER_SUPERUSER_RU minimum.
*/
if (! $PMA->user->isMinimum(PMA_USER_SUPERUSER_RU)) {
    throw new PMA_cmdException('illegal_operation');
}

/*
* Initiate Murmur connection.
*/
require PMA_DIR_INCLUDES.'iceConnection.inc';
if (! $PMA->murmurMeta->isConnected()) {
    throw new PMA_cmdException();
}

$sid = $_SESSION['page_vserver']['id'];

/*
* Get vserver proxy.
*/
if (is_null($prx = $PMA->murmurMeta->getServer($sid))) {
    throw new PMA_cmdException('Murmur_InvalidServerException');
}

$defaultConf = $PMA->murmurMeta->getDefaultConf();
/*
* Murmur port particularity.
*/
$defaultConf['port'] = (string) ($defaultConf['port'] + $sid - 1);
$customConf = $prx->getAllConf();
$settings = PMA_MurmurSettingsHelper::get($PMA->murmurMeta->getVersionInt());

/*
* CMD:
* Set vserver settings.
*/
if (isset($cmd->PARAMS['setConf'])) {

    foreach ($settings as $key => $array) {

        if (! isset($cmd->PARAMS[$key])) {
            continue;
        }

        $newValue = $cmd->PARAMS[$key];

        /*
        * Disallow Admins and SuperUsers to modify SuperAdmins parameters.
        */
        if ($array['right'] === 'SA' && ! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
            continue;
        }

        /*
        * Invalid form.
        */
        if ($key !== 'welcometext' && strlen($newValue) > 255) {
            $PMA->debugError('Too long value for: '.$key);
            continue;
        }

        /*
        * Empty value.
        */
        if ($newValue === '') {
            if ($array['type'] !== 'bool') {
                // Remove custom parameter if exists
                if (isset($customConf[$key])) {
                    /*
                    * Memo: setConf() with "zero", "one" or "multiple" spaces
                    * will alway remove the key in murmur DB.
                    */
                    $prx->setConf($key, '');
                }
            }
            continue;
        }

        /*
        * Do not add custom value if it's the same as the $defaultConf.
        */
        if (isset($defaultConf[$key]) && $newValue === $defaultConf[$key]) {
            // A custom value is set for the key, remove it.
            if (isset($customConf[$key])) {
                $prx->setConf($key, '');
            }
            continue;
        }

        /*
        * The custom value didn't change, do anything.
        */
        if (isset($customConf[$key]) && $newValue === $customConf[$key]) {
            continue;
        }

        /*
        * Set parameters.
        */

        // Host particularity.
        if ($key === 'host') {
            $prx->setConf($key, $newValue);
            if ($prx->isRunning()) {
                $PMA->message('host_modified_success');
            }
        // Port particularity.
        } elseif ($key === 'port') {
            if (checkPort($newValue)) {
                $prx->setConf($key, $newValue);
                if ($prx->isRunning()) {
                    $PMA->message('port_modified_success');
                }
            } else {
                $PMA->messageError('invalid_port');
            }
        // Registername particularity.
        } elseif ($key === 'registername') {
            if ($prx->validateChannelChars($newValue)) {
                $prx->setConf($key, $newValue);
            } else {
                $PMA->messageError('invalid_channel_name');
            }
        // Integer particularity
        } elseif ($array['type'] === 'integer') {
            if (ctype_digit($newValue)) {
                $prx->setConf($key, $newValue);
            } else {
                $PMA->messageError(array('invalid_numerical', $key));
            }
        // Default
        } else {
            $prx->setConf($key, $newValue);
        }
    }

/*
* CMD:
* Reset vserver settings.
*/
} elseif (isset($cmd->PARAMS['reset_setting'])) {

    $key = $cmd->PARAMS['reset_setting'];

    if ($key === 'key' OR $key === 'certificate') {

        if (! isset($cmd->PARAMS['confirmed'])) {
            throw new PMA_cmdExitException();
        }

        $prx->setConf($key, '');

        /*
        * Delete the private key when user delete the certificate.
        */
        if ($key === 'certificate') {
            $prx->setConf('key', '');
            if ($prx->isRunning()) {
                $PMA->message('certificate_modified_success');
            }
        }

    } else {

        $prx->setConf($key, '');

        if ($prx->isRunning()) {
            if ($key === 'host') {
                $PMA->message('host_modified_success');
            }
            if ($key === 'port') {
                $PMA->message('port_modified_success');
            }
        }
    }
/*
* CMD:
* Add a certificate (UPLOAD and FORM).
*/
} elseif (isset($_FILES['add_certificate']) OR isset($cmd->PARAMS['add_certificate'])) {

    if (! function_exists('openssl_pkey_export') OR ! function_exists('openssl_x509_export')) {
        throw new PMA_cmdException('php_openssl_module_not_found');
    }

    /*
    * Get PEM
    */
    if (isset($_FILES['add_certificate'])) {
        /*
        * Check for error on upload:
        */
        if (
            $_FILES['add_certificate']['error'] !== 0
            OR $_FILES['add_certificate']['size'] > MAX_FILE_SIZE_CERT
        ) {
            throw new PMA_cmdException('invalid_certificate');
        }

        $pem = file_get_contents($_FILES['add_certificate']['tmp_name']);

    } elseif (isset($cmd->PARAMS['add_certificate'])) {
        $pem = $cmd->PARAMS['add_certificate'];
    }

    /*
    * Separate private key and certificate.
    * Memo : Invalid PEM file will throw a php warning message.
    *
    * openssl_pkey_export() :
    * openssl-php need a valid "openssl.cnf" installed for this function
    * to operate correctly.
    */
    @openssl_pkey_export($pem, $privatekey);
    @openssl_x509_export($pem, $certificate);

    /*
    * Checks if the private key corresponds to the certificate.
    */
    if (! openssl_x509_check_private_key($certificate, $privatekey)) {
        throw new PMA_cmdException('invalid_certificate');
    }

    $prx->setConf('key', $privatekey);
    $prx->setConf('certificate', $certificate);

    if ($prx->isRunning()) {
        $PMA->message('certificate_modified_success');
    }
}
