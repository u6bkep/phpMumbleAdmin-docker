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
* Ice connection class for version 3.5.
*/
class PMA_MurmurConnectionIce35 extends PMA_MurmurConnectionIce34
{
    /*
    * Update loadSlicesDefinitions method for Murmur 3.5 and superior
    */
    protected function loadSlicesDefinitions()
    {
        if (1 === @include 'Ice.php') {
            if (is_file($this->slice_php_file_path)) {
                include $this->slice_php_file_path;
            } elseif (is_file($this->slice_php_custom_path)) {
                include $this->slice_php_custom_path;
            }
        }
    }

    /*
    * Update getProxy method for Murmur 3.5 and superior
    */
    protected function getProxy()
    {
        /*
        * Memo for Meta -e 1.0
        * see :
        * https://doc.zeroc.com/technical-articles/general-topics/encoding-version-1-1
        */
        try {
            $proxy = $this->ICE->stringToProxy('Meta -e 1.0 :tcp -h '.$this->host.' -p '.$this->port.' -t '.$this->timeout);
            $this->meta = Murmur_MetaPrxHelper::checkedCast($proxy)->ice_context($this->secret);
        } catch (Exception $e) {
            $this->throwException($e);
        }
    }
}
