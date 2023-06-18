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

class PMA_MurmurServerStats extends PMA_MurmurServer
{
    private function stats_start($function)
    {
        PMA_ice_queries_stats::start($function);
    }

    private function stats_stop()
    {
        PMA_ice_queries_stats::stop();
    }

    /*
    * **********************************************
    * Murmur_Server methods with queries_stats.
    * **********************************************
    */
    public function addChannel($name, $parent)
    {
        $this->stats_start(__function__);
        $id = $this->prx->addChannel($name, $parent);
        $this->stats_stop();

        return $id;
    }

    public function delete()
    {
        $this->stats_start(__function__);
        $this->prx->delete();
        $this->stats_stop();
    }

    public function getACL($id, &$aclList, &$aclGroup, &$inherit)
    {
        $this->stats_start(__function__);
        $this->prx->getACL($id, $aclList, $aclGroup, $inherit);
        $this->stats_stop();
    }

    public function getAllConf()
    {
        $this->stats_start(__function__);
        $array = $this->prx->getAllConf();
        $this->stats_stop();

        return $array;
    }

    public function getBans()
    {
        $this->stats_start(__function__);
        $array = $this->prx->getBans();
        $this->stats_stop();

        return $array;
    }

    public function getCertificateList($uid)
    {
        $this->stats_start(__function__);
        $array = $this->prx->getCertificateList($uid);
        $this->stats_stop();

        return $array;
    }

    public function getChannelState($id)
    {
        $this->stats_start(__function__);
        $obj = $this->prx->getChannelState($id);
        $this->stats_stop();

        return $obj;
    }

    public function getChannels()
    {
        $this->stats_start(__function__);
        $array = $this->prx->getChannels();
        $this->stats_stop();

        return $array;
    }

    public function getConf($key)
    {
        $this->stats_start(__function__);
        $str = $this->prx->getConf($key);
        $this->stats_stop();

        return $str;
    }

    public function getLog($first, $last)
    {
        $this->stats_start(__function__);
        $array = $this->prx->getLog($first, $last);
        $this->stats_stop();

        return $array;
    }

    public function getLogLen()
    {
        $this->stats_start(__function__);
        $len = $this->prx->getLogLen();
        $this->stats_stop();

        return $len;
    }

    public function getRegisteredUsers($filter)
    {
        $this->stats_start(__function__);
        $array = $this->prx->getRegisteredUsers($filter);
        $this->stats_stop();

        return $array;
    }

    public function getRegistration($id)
    {
        $this->stats_start(__function__);
        $array = $this->prx->getRegistration($id);
        $this->stats_stop();

        return $array;
    }

    public function getState($uid)
    {
        $this->stats_start(__function__);
        $obj = $this->prx->getState($uid);
        $this->stats_stop();

        return $obj;
    }

    public function getTexture($uid)
    {
        $this->stats_start(__function__);
        $array = $this->prx->getTexture($uid);
        $this->stats_stop();

        return $array;
    }

    public function getTree()
    {
        $this->stats_start(__function__);
        $obj = $this->prx->getTree();
        $this->stats_stop();

        return $obj;
    }

    public function getUptime()
    {
        $this->stats_start(__function__);
        $int = $this->prx->getUptime();
        $this->stats_stop();

        return $int;
    }

    public function getUsers()
    {
        $this->stats_start(__function__);
        $array = $this->prx->getUsers();
        $this->stats_stop();

        return $array;
    }

    public function hasPermission($session, $channelid, $perm)
    {
        $this->stats_start(__function__);
        $bool = $this->prx->hasPermission($session, $channelid, $perm);
        $this->stats_stop();

        return $bool;
    }

    public function id()
    {
        $this->stats_start(__function__);
        $id = $this->prx->id();
        $this->stats_stop();

        return $id;
    }

    public function isRunning()
    {
        $this->stats_start(__function__);
        $bool = $this->prx->isRunning();
        $this->stats_stop();

        return $bool;
    }

    public function kickUser($uid, $reason)
    {
        $this->stats_start(__function__);
        $this->prx->kickUser($uid, $reason);
        $this->stats_stop();
    }

    public function registerUser($array)
    {
        $this->stats_start(__function__);
        $uid = $this->prx->registerUser($array);
        $this->stats_stop();

        return $uid;
    }

    public function removeChannel($id)
    {
        $this->stats_start(__function__);
        $this->prx->removeChannel($id);
        $this->stats_stop();
    }

    public function sendMessage($uid, $text)
    {
        $this->stats_start(__function__);
        $this->prx->sendMessage($uid, $text);
        $this->stats_stop();
    }

    public function sendMessageChannel($uid, $sub, $text)
    {
        $this->stats_start(__function__);
        $this->prx->sendMessageChannel($uid, $sub, $text);
        $this->stats_stop();
    }

    public function setACL($id, $aclList, $aclGroup, $inherit)
    {
        $this->stats_start(__function__);
        $this->prx->setACL($id, $aclList, $aclGroup, $inherit);
        $this->stats_stop();
    }

    public function setBans($array)
    {
        $this->stats_start(__function__);
        $this->prx->setBans($array);
        $this->stats_stop();
    }

    public function setChannelState($chan)
    {
        $this->stats_start(__function__);
        $this->prx->setChannelState($chan);
        $this->stats_stop();
    }

    public function setConf($key, $value)
    {
        $this->stats_start(__function__);
        $this->prx->setConf($key, $value);
        $this->stats_stop();
    }

    public function setState($state)
    {
        $this->stats_start(__function__);
        $this->prx->setState($state);
        $this->stats_stop();
    }

    public function setSuperuserPassword($str)
    {
        $this->stats_start(__function__);
        $this->prx->setSuperuserPassword($str);
        $this->stats_stop();
    }

    public function setTexture($uid, $texture)
    {
        $this->stats_start(__function__);
        $this->prx->setTexture($uid, $texture);
        $this->stats_stop();
    }

    public function start()
    {
        $this->stats_start(__function__);
        $this->prx->start();
        $this->stats_stop();
    }

    public function stop()
    {
        $this->stats_start(__function__);
        $this->prx->stop();
        $this->stats_stop();
    }

    public function unregisterUser($uid)
    {
        $this->stats_start(__function__);
        $this->prx->unregisterUser($uid);
        $this->stats_stop();
    }

    public function updateRegistration($uid, $array)
    {
        $this->stats_start(__function__);
        $this->prx->updateRegistration($uid, $array);
        $this->stats_stop();
    }

    public function verifyPassword($name, $pw)
    {
        $this->stats_start(__function__);
        $result = $this->prx->verifyPassword($name, $pw);
        $this->stats_stop();

        return $result;
    }

/*
*
* Murmur_Server methods I didnt declared here
*
* addCallback
* addContextCallback
* addUserToGroup
* effectivePermissions
* getUserIds
* getUserNames
* redirectWhisperGroup
* removeCallback
* removeContextCallback
* removeUserFromGroup
* setAuthenticator
*
*/

}
