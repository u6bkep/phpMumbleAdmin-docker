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

class PMA_userInfos
{
    public $datas = array();

    public function add($desc, $value, $tooltip = '')
    {
        $data = new stdClass();

        $data->value = $value;
        $data->desc = $desc;
        $data->tooltip = $tooltip;

        $this->datas[] = $data;
    }
}

$widget  = new PMA_userInfos();

// IP address
$widget->add($TEXT['ip_addr'], $page->sessionObj->ip);
// Registered user ID
$uid = ($page->sessionObj->userid >= 0) ? $page->sessionObj->userid : $TEXT['unregistered'];
$widget->add($TEXT['registration_id'], $uid);
// User session
$widget->add($TEXT['session_id'], $page->sessionObj->session);
// Online uptime
$widget->add($TEXT['online'], PMA_datesHelper::uptime($page->sessionObj->onlinesecs));
// Idle uptime
$widget->add($TEXT['idle'], PMA_datesHelper::uptime($page->sessionObj->idlesecs));
// TCP mode
$text = $page->sessionObj->tcponly ? $TEXT['yes'] : $TEXT['no'];
$widget->add($TEXT['tcp_mode'], $text);
// Bandwidth
$widget->add($TEXT['bandwidth'], convertSize($page->sessionObj->bytespersec * 10), $TEXT['bandwidth_info']);
// TCP & UDP pings
if (isset($page->sessionObj->tcpPing, $page->sessionObj->udpPing)) {
    $widget->add($TEXT['tcp_ping'], round($page->sessionObj->tcpPing, 2).' ms', $TEXT['ping_info']);
    $widget->add($TEXT['udp_ping'], round($page->sessionObj->udpPing, 2).' ms', $TEXT['ping_info']);
}
// Mumble version
$widget->add($TEXT['mumble_client'], $page->sessionObj->release);
// OS version
$widget->add($TEXT['os'], $page->sessionObj->osversion);
// Certificate hash sha1
if (isset($page->sessionObj->certSha1)) {
    $widget->add($TEXT['cert_hash'], $page->sessionObj->certSha1);
}
