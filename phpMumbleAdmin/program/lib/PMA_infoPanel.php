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

class PMA_infoPanelObject
{
    public $datas = '';
    public $css = '';
    public $fill = false;
    public $occasional = false;
    public $invert = false;
}

class PMA_infoPanel
{
    private $datas = array();

    private function addDatas($obj)
    {
        $this->datas[] = $obj;
    }

    public function add($datas)
    {
        $obj = new PMA_infoPanelObject();
        $obj->datas = $datas;
        $this->addDatas($obj);
    }

    public function addRight($datas)
    {
        $obj = new PMA_infoPanelObject();
        $obj->datas = $datas;
        $obj->invert = true;
        $this->addDatas($obj);
    }

    public function addFill($datas)
    {
        $obj = new PMA_infoPanelObject();
        $obj->datas = $datas;
        $obj->fill = true;
        $this->addDatas($obj);
    }

    public function addFillOccas($datas)
    {
        $obj = new PMA_infoPanelObject();
        $obj->datas = $datas;
        $obj->fill = true;
        $obj->occasional = true;
        $this->addDatas($obj);
    }

    public function addFillRight($datas)
    {
        $obj = new PMA_infoPanelObject();
        $obj->datas = $datas;
        $obj->fill = true;
        $obj->occasional = true;
        $obj->invert = true;
        $this->addDatas($obj);
    }

    public function getDatas()
    {
        return $this->datas;
    }
}
