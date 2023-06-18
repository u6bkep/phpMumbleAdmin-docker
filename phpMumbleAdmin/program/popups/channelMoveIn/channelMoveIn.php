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

// Test scroll
// for ($i=100000; $i < 100150; ++$i) {
//     $page->onlineUsersList[$i] = PMA_MurmurObjectFactory::getUser();
//     $page->onlineUsersList[$i]->name = 'Fake name, fake session-'.$i.'        sdfdf <a  href=""       a>    dsfsdffsd                  sdfsdfsdff';
//     $page->onlineUsersList[$i]->session = $i;
//     $page->onlineUsersList[$i]->channel = 9932132133;
// }

$widget->scroll = array();

foreach ($page->onlineUsersList as $user) {
    // Show only users out of the channel
    if ($user->channel !== $page->channelObj->id) {
        $widget->scroll[] = $user;
    }
}
