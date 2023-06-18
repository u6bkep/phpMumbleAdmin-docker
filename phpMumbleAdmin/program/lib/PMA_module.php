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

class PMA_module
{
    /*
    * Properties table.
    */
    protected $properties = array();

    /*
    * Encode a string with HTML entities.
    */
    protected function htEnc($value)
    {
        return htmlEntities($value, ENT_QUOTES, 'UTF-8');
    }

    /*
    * Set a property.
    */
    public function set($key, $value)
    {
        if (is_string($value)) {
            $value = $this->htEnc($value);
        }
        $this->properties[$key] = $value;
    }

    /*
    * Check if a property is set.
    */
    public function is_set($key)
    {
        return isset($this->properties[$key]) ? true : false;
    }

    /*
    * Get the property value.
    */
    public function get($key)
    {
        return $this->properties[$key];
    }

    /*
    * Return a formated text.
    */
    public function sprintf($text, $key)
    {
        return sprintf($text, $this->properties[$key]);
    }

    public function chked($key)
    {
        if ($this->properties[$key] === true) {
            return 'checked="checked"';
        }
    }

    public function disabled($key)
    {
        if ($this->properties[$key] === true) {
            return 'disabled="disabled"';
        }
    }

    public function required($key)
    {
        if ($this->properties[$key] === true) {
            return 'required="required"';
        }
    }

    public function selected($key)
    {
        if ($this->properties[$key] === true) {
            return 'selected="selected"';
        }
    }
}
