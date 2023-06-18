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

class PMA_certificate
{
    private $PEM;
    private $dateTimeFormat;

    private $error = false;
    public $errorText;

    public $datas = array();

    public function setPEM($PEM)
    {
        $this->PEM = $PEM;
    }

    public function getPem()
    {
        return $this->PEM;
    }

    private function error($text)
    {
        $this->error = true;
        $this->errorText = $text;
    }

    public function hasError()
    {
        return $this->error;
    }

    public function addTitle($text)
    {
        $data = new stdClass();
        $data->title = true;
        $data->text = $text;
        $this->datas[] = $data;
    }

    public function addData($key, $text)
    {
        $data = new stdClass();
        $data->title = false;
        $data->key = $key;
        $data->text = $text;
        $this->datas[] = $data;
    }
}
