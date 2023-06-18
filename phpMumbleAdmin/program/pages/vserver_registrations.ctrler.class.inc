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

class PMA_ctrler_mumbleRegistrationObject
{
    public $name;
    public $email;
    public $desc;
    public $cert;
    public $lastActivity;

    public function __construct(array $reg)
    {
        $this->name = isset($reg[0]) ? $reg[0] : '';
        $this->email = isset($reg[1]) ? $reg[1] : '';
        $this->desc = isset($reg[2]) ? $reg[2] : '';
        $this->cert = isset($reg[3]) ? $reg[3] : '';
        $this->lastActivity = isset($reg[5]) ? $reg[5] : '';
    }
}

class PMA_ctrler_usersStatus
{
    private $online = array();

    public function setOnlineList(array $list)
    {
        $this->online = $list;
    }

    /*
    * Check if a registered user is online.
    *
    * @return array
    */
    public function check($uid)
    {
        $data = new stdClass();
        $data->sessionUrl = '';
        $data->isOnline = false;
        foreach ($this->online as $user) {
            if ($user->userid === $uid) {
                $data->sessionUrl = $user->session.'-'.$user->name;
                $data->isOnline = true;
                break;
            }
        }
        return $data;
    }
}
