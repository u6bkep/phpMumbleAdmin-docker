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
* Ice connection class for version 3.6.
*/
class PMA_MurmurConnectionIce37 extends PMA_MurmurConnectionIce36
{
    protected function checkSlicesDefinitions()
    {
        if (!interface_exists('Ice\ObjectFactory')) {
            $this->throwException('ice_could_not_load_Icephp_file');
        }
        if (!class_exists('\MumbleServer\MetaPrxHelper')) {
            $this->throwException('ice_no_slice_definition_found');
        }
    }

    /*
    * php-Ice 3.7 require a workaround to load Ice.php and slices definitions.
    * see includes/ice34Workaround.inc
    */
    protected function loadSlicesDefinitions()
    {
        // Do nothing.
    }

    protected function initalizeIce()
    {
        try {
            $this->ICE = \Ice\initialize();
        } catch (Exception $e) {
            $this->throwException($e);
        }
    }

    protected function getProxy()
    {
        /*
        * Memo for Meta -e 1.0
        * see :
        * https://doc.zeroc.com/technical-articles/general-topics/encoding-version-1-1
        */
        try {
            $proxy = $this->ICE->stringToProxy('Meta -e 1.0 :tcp -h '.$this->host.' -p '.$this->port.' -t '.$this->timeout);
            $this->meta = \MumbleServer\MetaPrxHelper::checkedCast($proxy)->ice_context($this->secret);
        } catch (Exception $e) {
            $this->throwException($e);
        }
    }
}
