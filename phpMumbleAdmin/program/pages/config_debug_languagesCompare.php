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
* Languages files extension.
*/
$extension = '.loc.php';

if ($PMA->cookie->get('lang') !== 'en_EN') {
    /*
    * Scan the english language directory.
    * English is the reference.
    */
    $page->dirScan = scanDir(PMA_DIR_LANGUAGES.'/en_EN/');
    /*
    * Check that user has selected a valid file.
    */
    $selected = '';
    if (isset($_GET['fileName'])) {
        $file = $_GET['fileName'].$extension;
        if (in_array($file, $page->dirScan)) {
            $selected = $_GET['fileName'];
            $page->filePath = $file;
            $page->datas = languages_compare($file, $PMA->cookie->get('lang'));
        }
    }
    /*
    * Setup files selection menu.
    */
    $page->menu = array();
    foreach ($page->dirScan as $file) {

        if (substr($file, -8) !== $extension) {
            continue;
        }
        $name = substr($file, 0, -8);

        $data = new stdClass();
        $data->name = $name;
        $data->css = '';
        if ($name === $selected) {
            $data->css = 'selected';
        }
        $page->menu[] = $data;
    }
}
