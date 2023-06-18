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
* CMD:
* Set language.
*/
if (isset($cmd->PARAMS['setLang'])) {
    $cmd->setRedirection('referer');
    $PMA->cookie->set('lang', $cmd->PARAMS['setLang']);

/*
* CMD:
* Set options.
*/
} elseif (isset($cmd->PARAMS['set_options'])) {

    if (! $PMA->user->isMinimum(PMA_USER_MUMBLE)) {
        throw new PMA_cmdException('illegal_operation');
    }
    $PMA->cookie->set('lang', $cmd->PARAMS['lang']);
    $PMA->cookie->set('skin', $cmd->PARAMS['skin']);
    $PMA->cookie->set('timezone', $cmd->PARAMS['timezone']);
    $PMA->cookie->set('time', $cmd->PARAMS['time']);
    $PMA->cookie->set('date', $cmd->PARAMS['date']);
    $PMA->cookie->set('installed_localeFormat', $cmd->PARAMS['locales']);
    $PMA->cookie->set('uptime', (int)$cmd->PARAMS['uptime']);
    $PMA->cookie->set('vserver_login', $cmd->PARAMS['vserver_login']);

/*
* CMD:
* Set defaults options.
*/
} elseif (isset($cmd->PARAMS['set_default_options'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    $cmd->setRedirection('referer');

    $PMA->config->set('default_lang', $cmd->PARAMS['lang']);
    $PMA->config->set('default_skin', $cmd->PARAMS['skin']);
    $PMA->config->set('default_timezone', $cmd->PARAMS['timezone']);
    $PMA->config->set('default_time', $cmd->PARAMS['time']);
    $PMA->config->set('default_date', $cmd->PARAMS['date']);
    $PMA->config->set('defaultSystemLocales', $cmd->PARAMS['systemLocales']);

/*
* CMD:
* Add a locales profile.
*/
} elseif (isset($cmd->PARAMS['add_locales_profile'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    $cmd->setRedirection('referer');

    $array = $PMA->config->get('systemLocalesProfiles');

    $key = $cmd->PARAMS['key'];
    $value = $cmd->PARAMS['val'];

    if ($key !== '' && $value !== '' && ! isset($array[$key]) && ! in_array($value, $array)) {
        $array[$key] = $value;
        natCaseSort($array);
        $PMA->config->set('systemLocalesProfiles', $array);
    }

/*
* CMD:
* Delete a locales profile.
*/
} elseif (isset($cmd->PARAMS['delete_locales_profile'])) {

    $key = $cmd->PARAMS['delete_locales_profile'];

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    $cmd->setRedirection('referer');

    $array = $PMA->config->get('systemLocalesProfiles');

    if ($key !== '' && isset($array[$key])) {
        unset($array[$key]);
        $PMA->config->set('systemLocalesProfiles', $array);
    }

/*
* CMD:
* Toggle infoPanel.
*/
} elseif (isset($cmd->PARAMS['toggle_infopanel'])) {

    if (! $PMA->user->isMinimum(PMA_USER_MUMBLE)) {
        throw new PMA_cmdException('illegal_operation');
    }
    $PMA->cookie->set('infoPanel', ! $PMA->cookie->get('infoPanel'));

/*
* CMD:
* Toggle highlight PMA logs.
*/
} elseif (isset($cmd->PARAMS['toggle_highlight_pmaLogs'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    $PMA->cookie->set('highlight_pmaLogs', ! $PMA->cookie->get('highlight_pmaLogs'));

/*
* CMD:
* Check for updates.
*/
} elseif (isset($cmd->PARAMS['check_for_update'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    $updates = new PMA_updates();
    if ($cmd->PARAMS['check_for_update'] === 'debug') {
        $updates->setDebugMode();
    }
    if ($updates->check()) {
        $PMA->message(array('new_pma_version', $updates->get('current_version')));
    } else {
        $PMA->messageError('no_update_found');
    }
    $PMA->app->set('updates', $updates->getCacheParameters());

/*
* CMD:
* Set general settings.
*/
} elseif (isset($cmd->PARAMS['set_settings_general'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }

    $PMA->config->set('siteTitle', $cmd->PARAMS['title']);
    $PMA->config->set('siteComment', $cmd->PARAMS['comment']);

    if (ctype_digit($cmd->PARAMS['auto_logout'])) {
        $PMA->config->set('auto_logout', (int)$cmd->PARAMS['auto_logout']);
    }
    if (ctype_digit($cmd->PARAMS['check_update'])) {
        $PMA->config->set('update_check', (int)$cmd->PARAMS['check_update']);
    }
    $PMA->config->set('murmur_version_url', isset($cmd->PARAMS['murmurVersionUrl']));
    $PMA->config->set('ddl_auth_page', isset($cmd->PARAMS['ddlAuthPage']));

    if (ctype_digit($cmd->PARAMS['ddlRefresh'])) {
        $PMA->config->set('ddl_refresh', (int)$cmd->PARAMS['ddlRefresh']);
    }
    $PMA->config->set('ddl_show_cache_uptime', isset($cmd->PARAMS['show_uptime']));
    $PMA->config->set('show_avatar_sa', isset($cmd->PARAMS['show_avatar_sa']));
    $PMA->config->set('IcePhpIncludePath', $cmd->PARAMS['incPath']);

/*
* CMD:
* Set autoban settings.
*/
} elseif (isset($cmd->PARAMS['set_settings_autoban'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    if (ctype_digit($cmd->PARAMS['attempts'])) {
        $PMA->config->set('autoban_attempts', (int)$cmd->PARAMS['attempts']);
    }
    if (ctype_digit($cmd->PARAMS['timeFrame'])) {
        $PMA->config->set('autoban_frame', (int)$cmd->PARAMS['timeFrame']);
    }
    if (ctype_digit($cmd->PARAMS['duration'])) {
        $PMA->config->set('autoban_duration', (int)$cmd->PARAMS['duration']);
    }

/*
* CMD:
* Set SMTP settings.
*/
} elseif (isset($cmd->PARAMS['set_settings_smtp'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    $PMA->config->set('smtp_host', $cmd->PARAMS['host']);
    $PMA->config->set('smtp_port', (int)$cmd->PARAMS['port']);
    $PMA->config->set('smtp_default_sender_email', $cmd->PARAMS['default_sender']);
    $PMA->config->set('debug_email_to', $cmd->PARAMS['email']);

/*
* CMD:
* Set logs settings.
*/
} elseif (isset($cmd->PARAMS['set_settings_logs'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    // murmur logs
    if (ctype_digit($cmd->PARAMS['murmur_logs_size']) OR $cmd->PARAMS['murmur_logs_size'] === '-1') {
        $PMA->config->set('vlogs_size', (int)$cmd->PARAMS['murmur_logs_size']);
    }
    $PMA->config->set('vlogs_admins_active', isset($cmd->PARAMS['activate_admins']));
    $PMA->config->set('vlogs_admins_highlights', isset($cmd->PARAMS['adm_hightlights_logs']));
    // PMA logs
    if ($PMA->user->isMinimum(PMA_USER_SUPERADMIN)) {
        if (ctype_digit($cmd->PARAMS['log_keep'])) {
            $PMA->config->set('pmaLogs_keep', (int)$cmd->PARAMS['log_keep']);
        }
        $PMA->config->set('pmaLogs_SA_actions', isset($cmd->PARAMS['log_SA']));
    }

/*
* CMD:
* Set tables settings.
*/
} elseif (isset($cmd->PARAMS['set_settings_tables'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    if (ctype_digit($cmd->PARAMS['overview'])) {
        $PMA->config->set('table_overview', (int)$cmd->PARAMS['overview']);
    }
    if (ctype_digit($cmd->PARAMS['users'])) {
        $PMA->config->set('table_users', (int)$cmd->PARAMS['users']);
    }
    if (ctype_digit($cmd->PARAMS['bans'])) {
        $PMA->config->set('table_bans', (int)$cmd->PARAMS['bans']);
    }
    $PMA->config->set('show_online_users', isset($cmd->PARAMS['totalOnline']));
    $PMA->config->set('show_online_users_sa', isset($cmd->PARAMS['totalOnlineSa']));
    $PMA->config->set('show_uptime', isset($cmd->PARAMS['uptime']));
    $PMA->config->set('show_uptime_sa', isset($cmd->PARAMS['uptimeSa']));

/*
* CMD:
* Set external viewer settings.
*/
} elseif (isset($cmd->PARAMS['set_settings_ext_viewer'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    $PMA->config->set('external_viewer_enable', isset($cmd->PARAMS['enable']));
    if (ctype_digit($cmd->PARAMS['width'])) {
        $PMA->config->set('external_viewer_width', (int)$cmd->PARAMS['width']);
    }
    if (ctype_digit($cmd->PARAMS['height'])) {
        $PMA->config->set('external_viewer_height', (int)$cmd->PARAMS['height']);
    }
    $PMA->config->set('external_viewer_vertical', isset($cmd->PARAMS['vertical']));
    $PMA->config->set('external_viewer_scroll', isset($cmd->PARAMS['scroll']));

/*
* CMD:
* Set mumble users settings.
*/
} elseif (isset($cmd->PARAMS['set_mumble_users'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    $PMA->config->set('allowOfflineAuth', isset($cmd->PARAMS['allowOfflineAuth']));
    $PMA->config->set('SU_auth', isset($cmd->PARAMS['allowSuperUserAuth']));
    $PMA->config->set('SU_edit_user_pw', isset($cmd->PARAMS['allowSuperUserEditPw']));
    $PMA->config->set('SU_start_vserver', isset($cmd->PARAMS['allowSuperUserStartSrv']));
    $PMA->config->set('SU_ru_active', isset($cmd->PARAMS['allowSuperUserRuClass']));
    $PMA->config->set('RU_auth', isset($cmd->PARAMS['allowRuAuth']));
    $PMA->config->set('RU_delete_account', isset($cmd->PARAMS['allowRuDelAccount']));
    $PMA->config->set('RU_edit_login', isset($cmd->PARAMS['allowRuModifyLogin']));
    $PMA->config->set('pw_gen_active', isset($cmd->PARAMS['pwGenActive']));

/*
* CMD:
* Set passwords requests settings.
*/
} elseif (isset($cmd->PARAMS['set_pw_requests_options'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    $PMA->config->set('pw_gen_explicit_msg', isset($cmd->PARAMS['explicit_msg']));
    $PMA->config->set('pw_gen_sender_email', $cmd->PARAMS['sender_email']);

    if (ctype_digit($cmd->PARAMS['pending_delay'])) {
        $PMA->config->set('pw_gen_pending', (int)$cmd->PARAMS['pending_delay']);
    }
/*
* CMD:
* Set debug settings.
*/
} elseif (isset($cmd->PARAMS['set_settings_debug'])) {

    if (! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)) {
        throw new PMA_cmdException('illegal_operation');
    }
    if (ctype_digit($cmd->PARAMS['mode'])) {
        $PMA->config->set('debug', (int)$cmd->PARAMS['mode']);
    }
    $PMA->config->set('debug_session', isset($cmd->PARAMS['session']));
    $PMA->config->set('debug_object', isset($cmd->PARAMS['object']));
    $PMA->config->set('debug_stats', isset($cmd->PARAMS['stats']));
    $PMA->config->set('debug_messages', isset($cmd->PARAMS['messages']));
    $PMA->config->set('debug_select_flag', isset($cmd->PARAMS['flag']));

/*
* CMD:
* Send a debug settings.
*/
} elseif (isset($cmd->PARAMS['send_debug_email'])) {

    if (
        ! $PMA->user->isMinimum(PMA_USER_ROOTADMIN)
        OR $PMA->config->get('debug') < 1
    ) {
        throw new PMA_cmdException('illegal_operation');
    }
    /*
    * Setup mail object
    */
    $mail = new PMA_mail();
    $mail->setHost($PMA->config->get('smtp_host'));
    $mail->setPort($PMA->config->get('smtp_port'));
    $mail->setDefaultSender($PMA->config->get('smtp_default_sender_email'));
    $mail->setXmailer(PMA_PROJECT_NAME);
    $mail->addTo($PMA->config->get('debug_email_to'), 'Debug name');
    $mail->setSubject('[DEBUG MAIL]: '.PMA_PROJECT_NAME);
    $mail->setMessage('This is an automatique debug message sent by '.PMA_PROJECT_NAME);
    /*
    * Send mail
    */
    $mail->send_mail();
    foreach ($mail->smtpDialogues as $dial) {
        $PMA->debug($dial->debug);
    }
    if ($mail->smtpError) {
        $PMA->log('smtp.error', $mail->smtpErrorMessage);
    }
    if ($mail->smtpError) {
        $PMA->messageError('debug_mail_failed');
    } else {
        $PMA->message('debug_mail_succeed');
    }
}

