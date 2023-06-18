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

class filtersMenu
{
    public $menu = array();

    private $imgActive = '';
    private $imgInactive = '';

    public function setImgActive($src)
    {
        $this->imgActive = $src;
    }

    public function setImgInactive($src)
    {
        $this->imgInactive = $src;
    }

    private function getImgSrc($boolean)
    {
        return ($boolean === true) ? $this->imgActive : $this->imgInactive;
    }

    public function addFilterLink($cmd, $text, $img)
    {
        $filter = new stdClass();
        $filter->cmd = $cmd;
        $filter->text = $text;
        $filter->img = $this->getImgSrc($img);
        $this->menu[] = $filter;
    }

    public function addText($text, $img = '')
    {
        $filter = new stdClass();
        $filter->text = $text;
        $filter->img = $img;
        $this->menu[] = $filter;
    }

    public function addSeparation()
    {
        $filter = new stdClass();
        $filter->separation = true;
        $this->menu[] = $filter;
    }
}
