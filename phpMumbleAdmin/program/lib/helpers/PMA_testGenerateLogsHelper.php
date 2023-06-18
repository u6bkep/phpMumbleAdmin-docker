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

class PMA_testGenerateLogsHelper
{
    /*
    * Memo:
    * start() et stop() generate 3 logs.
    * Avarage of 15K of logs is require to reach Ice_MemoryLimitException
    * So a loop of 6000 * 3 is enought.
    *
    * Memo:
    * Duration of a loop of 500 is about 240 secondes.
    *
    * Memo:
    * On Linux, the max_execution_time directive has no effect when a script
    * do stream operations. So this function will run until the end.
    *
    * Example :
    * PMA_testGenerateLogsHelper::generateLogs($PMA->murmurMeta->getServer(13));
    * The server id 13 is the server dedicated to test Ice_MemoryLimitException for me.
    *
    */
    public static function generateLogs($prx)
    {
        if (is_object($prx)) {

            $start = microTime();

            for($i = 0; $i < 500; ++$i) {
                try {
                    $prx->start();
                    $prx->stop();
                } catch (exception $e) {
                    $prx->stop();
                    $prx->start();
                }
            }

            echo 'duration: '.PMA_statsHelper::duration($start).' secondes<br />';
            die('ok !!!');

        } else {
            die($prx.' is not a valid Murmur prx object');
        }
    }
}
