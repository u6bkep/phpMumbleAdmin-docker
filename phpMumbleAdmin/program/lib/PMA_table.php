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

class PMA_table
{
    /*
    * The minimum of lines a table MUST have.
    */
    const MINIMUM_LINES = 10;

    public $datas = array();
    protected $columns = array();
    protected $pagingMenu = array();
    /*
    * Sort variables.
    */
    protected $defaultSort;
    protected $sort;
    protected $reverse = false;
    /*
    * Paging variables.
    */
    protected $maxPerPage = 10; // Max lines of datas per page (default 10 lines).
    protected $currentPage;
    protected $totalOfPages;

    public function __construct($datas)
    {
        $this->datas = $datas;
    }

    public function __get($key)
    {
        return $this->$key;
    }

    public function setMaxPerPage($max)
    {
        $this->maxPerPage = $max;
    }

    public function setNavigation($nav)
    {
        $this->defaultSort = $nav['defaultSort'];
        $this->sort = $nav['sort'];
        $this->reverse = isset($nav['reverse']);
        $this->currentPage = $nav['page'];
    }

    /*
    * Add columns href and text.
    */
    public function sortColumn($key, $text, $short = false)
    {
        $column = new stdClass();
        $column->text = $text;
        $column->href = '?sort='.$key;

        if ($key === $this->sort) {
            if ($this->reverse) {
                $img = '<img src="'.PMA_IMG_ARROW_UP.'" alt="" />';
            } else {
                $img = '<img src="'.PMA_IMG_ARROW_DOWN.'" alt="" />';
                $column->href .= '&amp;reverse=true';
            }
            if ($short === true) {
                $column->text = $img;
            } else {
                $column->text .= $img;
            }
        }
        $this->columns[$key] = $column;
    }

    public function getColText($key)
    {
        return $this->columns[$key]->text;
    }

    public function getColHref($key)
    {
        return $this->columns[$key]->href;
    }

    public function sortDatas()
    {
        if (isset($this->sort, $this->defaultSort)) {
            uasort($this->datas, array('self', 'defaultCmp'));
        }
        if ($this->reverse) {
            $this->datas = array_reverse($this->datas, true);
        }
    }

    /*
    * defaultCmp() - Compare with a default key on equality.
    *
    * Compare table keys, if values are equal, compare default keys.
    * On empty string value, sort at last by inversing the result
    * (PHP by default sort empty string value at first).
    */
    protected function defaultCmp($a, $b)
    {
        $key = $this->sort;
        $default = $this->defaultSort;
        $aKey = $a->$key;
        $bKey = $b->$key;
        $aDefault = $a->$default;
        $bDefault = $b->$default;

        $result = strNatCaseCmp($aKey, $bKey);

        if ($result === 0) {
            $result = strNatCaseCmp($aDefault, $bDefault);
        } elseif ($aKey === '' OR $bKey === '') {
            // Inverse empty string.
            $result = 0 - $result;
        } elseif (is_bool($aKey)) {
            // Inverse boolean.
            $result = 0 - $result;
        }
        return $result;
    }

    /*
    * Chunk $datas array to keep the current page only.
    */
    public function pagingDatas()
    {
        $total = count($this->datas);

        if ($this->maxPerPage > 0) {
            $this->totalOfPages = (int) ceil($total / $this->maxPerPage);
        } else {
            $this->totalOfPages = 1;
        }
        // Current page can't be null or negative
        if (! is_int($this->currentPage) OR $this->currentPage < 1) {
            $this->currentPage = 1;
        }
        // Current page can't be superior than the total of page
        if ($this->currentPage > $this->totalOfPages) {
            $this->currentPage = $this->totalOfPages;
        }
        if ($this->totalOfPages > 1) {
            $chunk = array_chunk($this->datas, $this->maxPerPage, true);
            $this->datas = $chunk[$this->currentPage -1];
        }
    }

    /*
    * Get table paging menu.
    */
    public function getPagingMenu()
    {
        if (! isset($this->contructPagingMenuDone)) {

            $this->contructPagingMenuDone = true; // Do it once.

            if ($this->totalOfPages === 0) {
                $range = array();
            // Less than 5 pages of range.
            } elseif ($this->totalOfPages <= 5) {
                $range = range(1, $this->totalOfPages);
            } else {
                // 3 first pages range
                if ($this->currentPage <= 3) {
                    $range = range(1, 5);
                // 3 last pages range
                } elseif (($this->totalOfPages - $this->currentPage) <= 2) {
                    $range = range($this->totalOfPages -4, $this->totalOfPages);
                // All others range
                } else {
                    $range = range($this->currentPage -2, $this->currentPage +2);
                }
            }
            // Add pages range menu
            foreach ($range as $page) {
                $obj = new stdClass();
                $obj->page = $page;
                $obj->selected = ($this->currentPage === $page);
                $this->pagingMenu[] = $obj;
            }
        }
    }

    /*
    * Add empty datas to have a minimum number of line.
    */
    public function getMinimumLines()
    {
        $minimum = self::MINIMUM_LINES;
        $total = count($this->datas);
        if ($minimum > $total) {
            for ($i = $total; $i < $minimum; ++$i) {
                $this->datas[] = null;
            }
        }
    }
}
