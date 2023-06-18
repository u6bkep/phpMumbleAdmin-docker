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

$TEXT['ice_error'] = 'ICE fatal error';
$TEXT['ice_error_unauth'] = 'We had encountered an error with ICE.<br />Only system admins can login for the moment.';
$TEXT['ice_error_common'] = 'PhpMumbleAdmin failed to connect to ICE interface.';

// Explicit Ice errors
$TEXT['phpIce_version_not_supported'] = 'php-Ice module version is not supported.<br />3.4 or superior is required.';
$TEXT['ice_module_not_found'] = 'php-Ice module not found';
$TEXT['Ice_ConnectionRefusedException'] = 'Connection refused';
$TEXT['Ice_ConnectTimeoutException'] = 'Connection timeout';
$TEXT['ice_no_slice_definition_found'] = 'No slice definition found.<br />Please select a slice-php file.';
$TEXT['Murmur_InvalidSecretException'] = 'The password is incorrect';
$TEXT['ice_invalid_slice_file'] = 'Invalid slice definition file';
$TEXT['ice_could_not_load_Icephp_file'] = '"Ice.php" file not found';
$TEXT['ice_invalid_murmur_version'] = 'Invalid murmur version';
$TEXT['Ice_DNSException'] = 'Host not found';
$TEXT['Ice_UnknownErrorException'] = 'Unknown error with Ice.';

$TEXT['iceprofiles_admin_none'] = 'You are currently not allowed to access any servers. Please refer this error to your admin';

// Messages
$TEXT['Murmur_InvalidChannelException'] = 'This channel do not exists.';
$TEXT['Murmur_InvalidServerException'] = 'This server do not exists.';
$TEXT['Murmur_InvalidSecretException_write'] = 'You do not have ICE write access.<br />You must specify the "icesecretwrite" password to PMA';
$TEXT['Murmur_InvalidSessionException'] = 'This user is not connected.';
$TEXT['Murmur_InvalidUserException'] = 'This mumble registration do not exists.';
$TEXT['Murmur_NestingLimitException'] = 'Channel nesting limit reached';
$TEXT['Murmur_ServerBootedException'] = 'The server has been stopped during the update. Your last action has not been saved';
$TEXT['Murmur_ServerFailureException'] = 'The server couldn\'t start.<br />Please check the server logs';
$TEXT['Murmur_UnknownException'] = 'An unknown ICE error has occurred';
$TEXT['auth_error'] = 'Authentication failure';
$TEXT['auth_su_disabled'] = 'SuperUser login disabled';
$TEXT['auth_ru_disabled'] = 'Registered user login disabled';
$TEXT['change_pw_error'] = 'Error, the password has not been changed';
$TEXT['change_pw_success'] = 'The password has been changed';
$TEXT['invalid_bitmask'] = 'Invalid mask';
$TEXT['invalid_channel_name'] = 'Invalid channel name character';
$TEXT['invalid_username'] = 'Invalid user name character';
$TEXT['invalid_certificate'] = 'The submitted certificate is invalid.';
$TEXT['auth_vserver_stopped'] = 'The server is stopped for the moment - Please try later';
$TEXT['user_already_registered'] = 'This user is already registered - He must reconnect to the server to change his status to authenticated user';
$TEXT['children_channel'] = 'You can\'t move a channel to a child channel';
$TEXT['username_exists'] = 'This username already exists';
$TEXT['gen_pw_mail_sent'] = 'A confirmation email has been sent to your email address.<br />Please follow the instructions to generate a new password.';
$TEXT['web_access_disabled'] = 'Web access to the server is disabled';
$TEXT['vserver_dont_allow_HTML'] = 'The server does not allow HTML tags';
$TEXT['please_authenticate'] = 'Please sign-in';
$TEXT['iceProfile_sessionError'] = 'An error occured during your session - For security reason, please log in again';
$TEXT['gen_pw_authenticated'] = 'You must be unauthentified to process a password generation request. Please logout and retry';
$TEXT['certificate_modified_success'] = 'Certificate has been modified with success<br />You have to restart the server';
$TEXT['host_modified_success'] = 'Host parameter has been modified with success<br />You have to restart the server';
$TEXT['port_modified_success'] = 'Port has been modified with success<br />You have to restart the server';
$TEXT['illegal_operation'] = 'Illegal operation';
$TEXT['vserver_reset_success'] = 'Configuration has been reseted';
$TEXT['new_su_pw'] = 'New password for SuperUser: %s'; // %s new SuperUser password
$TEXT['registration_deleted_success'] = 'Registration has been deleted';
$TEXT['gen_pw_error'] = 'An error occured and PMA can\'t handle your password generation request';
$TEXT['gen_pw_invalid_server_id'] = 'The server ID is incorrect and PMA can\'t handle your password generation request';
$TEXT['gen_pw_invalid_username'] = 'The user name do not exists and PMA can\'t handle your password generation request';
$TEXT['gen_pw_su_denied'] = 'You can\'t request a password generation for SuperUser account';
$TEXT['gen_pw_empty_email'] = 'No email address for the account found and PMA can\'t handle your password generation request';
$TEXT['new_pma_version'] = 'PhpMumbleAdmin %s has been released';  // %s = new PMA version
$TEXT['no_update_found'] = 'No update found';
$TEXT['registration_created_success'] = 'Registration has been created';
$TEXT['Ice_MemoryLimitException_logs'] = 'The server logs are too big.<br />Please tell your admin to decrease logs requests.<br />PMA has forced a request for 100 lines.';
$TEXT['vserver_created_success'] = 'Virtual server has been created';
$TEXT['vserver_deleted_success'] = 'Virtual server has been deleted';
$TEXT['refuse_cookies'] = 'Cookies are disabled.<br />You must accept cookies to be able to authenticate.';
$TEXT['parameters_updated_success'] = 'All servers have been configured with success';
$TEXT['vserver_offline'] = 'The server is offline.';
$TEXT['vserver_offline_info'] = 'You can\'t manage channels, registrations or bans while the server is offline.';
