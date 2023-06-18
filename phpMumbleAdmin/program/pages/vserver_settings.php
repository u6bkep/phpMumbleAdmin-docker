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

pmaLoadLanguage('vserver_settings');

/*
* Get static vserver settings datas.
*/
$settings = PMA_MurmurSettingsHelper::get($PMA->murmurMeta->getVersionInt());
unset($settings['welcometext'], $settings['certificate'], $settings['key']);
/*
* Get default configuration,
* apply Murmur default port workaround
*/
$defaultConfig = $PMA->murmurMeta->getDefaultConf();
$defaultConfig['port'] += $prx->getSid() - 1;
/*
* Get custom configuration from $prx.
*/
$customConfig = $prx->getAllConf();
/*
* Construct HTML datas.
*/
$page->settingsDatas = getServerSettingsDatas(
    $settings,
    $defaultConfig,
    $customConfig,
    $PMA->user->isMinimum(PMA_USER_ROOTADMIN)
);
/*
* DEBUG:
* Display hidden parameters
*/
$page->settingsDebug = array();
if ($PMA->config->get('debug') > 0) {
    foreach ($settings as $key => $array) {
        unset($customConfig[$key]);
    }
    unset($customConfig['welcometext'], $customConfig['key'], $customConfig['certificate']);
    foreach ($customConfig as $key => $value) {
        $page->settingsDebug[$key]['key'] = $key;
        $page->settingsDebug[$key]['value'] = $value;
    }
}
