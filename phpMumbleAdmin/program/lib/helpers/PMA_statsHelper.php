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

class PMA_statsHelper
{
    /*
    * Calculate mircotime difference betwin start and end of a timestamp.
    *
    * @return float
    */
    public static function duration($start)
    {
        if (is_float($start)) {
            $duration = microTime(true) - $start;
        } else {
            list($start_dec, $start_sec) = explode(' ', $start);
            list($end_dec, $end_sec) = explode(' ', microTime());
            $duration = $end_sec - $start_sec + $end_dec - $start_dec;
        }

        if ($duration > 0.001) {
            $duration = round($duration, 3);
        } else {
            $duration = 0.001;
        }
        return $duration;
    }

    /*
    * Return memory peak usage during a php script execution
    */
    public static function memory()
    {
        return convertSize(memory_get_peak_usage(), 'byte');
    }

    public static function iceQueries()
    {
        if (class_exists('PMA_ice_queries_stats', false)) {
            return PMA_ice_queries_stats::get_global_stats();
        }
    }
}
