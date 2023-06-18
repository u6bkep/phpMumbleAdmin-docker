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

class PMA_dates
{
    /*
    * DATE format.
    */
    private $date;
    /*
    * TIME format.
    */
    private $time;
    /*
    * DATETIME format.
    */
    private $dateTime;
    /*
    * UPTIME format.
    */
    private $uptime;

    public function __construct($dateFormat, $timeFormat)
    {
        $this->date = $dateFormat;
        $this->time = $timeFormat;
        $this->dateTime = $dateFormat.' - '.$timeFormat;
    }

    public function setUptimeFormat($format)
    {
        $this->uptime = $format;
    }

    public function getDateTimeFormat()
    {
        return $this->dateTime;
    }

    /*
    * On NULL timestamp, return current timestamp.
    */
    private function getTimestamp($timestamp)
    {
        if (is_null($timestamp)) {
            $timestamp = time();
        }
        return $timestamp;
    }

    public function strDate($timestamp = null)
    {
        $timestamp = $this->getTimestamp($timestamp);
        return strftime($this->date, $timestamp);
    }

    public function strTime($timestamp = null)
    {
        $timestamp = $this->getTimestamp($timestamp);
        return strftime($this->time, $timestamp);
    }

    public function strDateTime($timestamp = null)
    {
        $timestamp = $this->getTimestamp($timestamp);
        return strftime($this->dateTime, $timestamp);
    }

    /*
    * UPTIME format.
    *
    * $format = 1 : 250 days 23:59:59
    * $format = 2 : 250 days 23:59
    * $format = 3 : 250 days
    */
    public function strUptime($ts, $format = null)
    {
        global $TEXT;

        if (is_null($format)) {
            $format = $this->uptime;
        }

        $days = (int) floor($ts / 86400);
        $ts %= 86400;
        $hours = sprintf('%02d', floor($ts / 3600));
        $ts %= 3600;
        $mins = sprintf('%02d', floor($ts / 60));
        $secs = $ts % 60;
        $secs = sprintf('%02d', $secs);

        $str = '';

        if ($days === 1) {
            $str = $days.' '.$TEXT['day'].' ';
        } elseif ($days >= 2) {
            $str = $days.' '.$TEXT['days'].' ';
        }

        if ($format === 1) {
            $str .= $hours.'h'.$mins.'m'.$secs.'s';
        } elseif ($format === 2 OR ($format === 3 && $days === 0)) {
            $str .= $hours.'h'.$mins.'m';
        }
        return strToLower($str);
    }
}
