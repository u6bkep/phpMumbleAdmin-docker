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
* Core of the PMA project.
*/
class PMA_core
{
    /*
    * Messages table.
    */
    private $messages = array();

    /*
    * Singleton
    */
    public static function getInstance()
    {
        static $instance;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }

    /*
    * Singleton
    * Private constructor to disallow direct class creation.
    */
    private function __construct() {}

    /*
    * Add an array of user space messages
    */
    public function mergeMessages($array)
    {
        $this->messages = array_merge_recursive($array, $this->messages);
    }

    /*
    * Add a debug message.
    */
    public function debug($message, $level = 1, $error = false)
    {
        $array = array(
            'level' => $level,
            'error' => $error,
            'msg' => $message
        );
        $this->messages['debugs'][] = $array;
    }

    /*
    * Add a debug message with error flag.
    */
    public function debugError($message)
    {
        $this->debug($message, 1, true);
    }

    /*
    * Get all debugs messages.
    *
    * @return array.
    */
    public function getDebugs()
    {
        return $this->messages['debugs'];
    }

    /*
    * Add an user space message.
    */
    public function message($key, $type = 'success')
    {
        if (is_array($key)) {
            $message['key'] = $key[0];
            $message['sprintf'] = $key[1];
        } else {
            $message['key'] = $key;
        }
        $message['type'] = $type;

        $this->messages['user'][] = $message;
    }

    /*
    * Add an user space message with error flag.
    */
    public function messageError($key)
    {
        $this->message($key, 'error');
    }

    /*
    * Get all debugs messages.
    *
    * @return array.
    */
    public function getMessages()
    {
        if (isset($this->messages['user'])) {
            return $this->messages['user'];
        } else {
            return array();
        }
    }

    /*
    * Set Ice error string.
    *
    * @return void.
    */
    public function setIceError($error)
    {
        $this->messages['iceError'] = (string)$error;
    }

    /*
    * get Ice error string.
    *
    * @return string.
    */
    public function getIceError()
    {
        return $this->messages['iceError'];
    }

    /*
    * Check if PMA has encoutered an error with Ice.
    *
    * @return boolean.
    */
    public function hasIceError()
    {
        return isset($this->messages['iceError']);
    }

    /*
    * Redirection
    */
    public function redirection($redirection = null)
    {
        if ($redirection === null) {
            $redirection = './';
        }
        /*
        * Shutdown.
        */
        $this->shutdown();
        $this->debug(__method__);
        /*
        * Cache all messages in $_SESSION.
        */
        $this->session->cacheMessages($this->messages);
        /*
        * Setup headers.
        */
        header('Status: 303 See other');
        header('location:'.$redirection);
        /*
        * Make sur to not execute extra code before redirection.
        */
        die();
    }

    /*
    * Write log in a file.
    */
    public function log($level, $message, $file = PMA_FILE_LOGS)
    {
        /*
        * 'a' = Open for writing only;
        * place the file pointer at the end of the file.
        * If the file does not exist, attempt to create it.
        */
        $fp = @fopen($file, 'ab');

        if (is_resource($fp)) {
            /*
            * This function print a human readable date time inside the log
            * file, use the default timezone for it.
            */
            @date_default_timezone_set($this->cookie->get('default_timezone'));

            /*
            * MEMO :
            * [0]timestamp:::[1]dateTime:::[2]level:::[3]ip:::[4]message:::[5]EOL
            */
            $ts = time();
            $date = date('H:i:s - Y-m-d', $ts);
            $level = '['.$level.']';
            $ip = $_SERVER['REMOTE_ADDR'];

            /*
            * Back to user timezone
            */
            @date_default_timezone_set($this->cookie->get('timezone'));

            /*
            * Write the log.
            */
            fwrite($fp, $ts.':::'.$date.':::'.$level.':::'.$ip.':::'.$message.':::'.PHP_EOL);

            /*
            * Close the log file.
            */
            fclose($fp);
        }
    }

    /*
    * Logout PMA user helper.
    */
    public function logout()
    {
        $this->user->resetAuth();
        $this->router->resetNavigation();
        unset($_SESSION['page_vserver']);
    }

    /*
    * Fatal error
    */
    public function fatalError($message = '')
    {
        die('<h3 style="color:red;">PhpMumbleAdmin fatal error</h3>'.$message);
    }

    /*
    * Shutdown operations.
    */
    public function shutdown()
    {
        if (! isset($this->shutdown)) {
            // Allow once, as PMA can call this method during the script.
            $this->shutdown = true;
            $this->debug(__method__, 3);
            $this->db->updateQueued();
            foreach ($this->db->getQueuedKey() as $key) {
                $this->debug(__method__ .' '.$key.' datas updated', 3);
            }
            if ($this->cookie->update()) {
                $this->debug(__method__ .' Cookie updated', 3);
            }
            $this->debug(__method__ .' Update router history', 3);
            $this->router->saveHistory();
        }
    }
}

