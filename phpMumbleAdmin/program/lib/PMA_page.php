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
* Pages controller.
*/
class PMA_page
{
    /*
    * Pages files absolute path.
    */
    private $path;

    /*
    * Page ID.
    */
    private $id;

    /*
    * Page view path.
    */
    private $view;

    /*
    * Page error message string.
    */
    private $errorMessage = '';

    /*
    * Classes table.
    */
    private $classes = array();

    /*
    * Controllers table.
    */
    private $controllers = array();

    /*
    * Set pages files absolute path.
    */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /*
    * Add a common controller with a class file if exists.
    */
    public function addCommonController($id)
    {
        $class = $this->path.$id.'.ctrler.class.inc';
        $ctrl = $this->path.$id.'.ctrler.inc';

        if (is_file($class)) {
            $this->classes[] = $class;
        }
        $this->controllers[] = $ctrl;
    }

    /*
    * Enable a page by setup it's ID.
    */
    public function enable($id)
    {
        $this->id = $id;

        $class = $this->path.$id.'.class.php';
        $ctrl = $this->path.$id.'.php';

        if (is_file($class)) {
            $this->classes[] = $class;
        }
        if (is_file($ctrl)) {
            $this->controllers[] = $ctrl;
        }
        $this->setView($id);
    }

    /*
    * @return string
    */
    public function getID()
    {
        return $this->id;
    }

    /*
    * @return array
    */
    public function getClasses()
    {
        return $this->classes;
    }

    /*
    * @return array
    */
    public function getControllers()
    {
        return $this->controllers;
    }

    public function setView($id)
    {
        $this->view = $this->path.$id.'.view.php';
    }

    /*
    * Set an alternative view path (with an absolute path).
    */
    public function setAltViewPath($path)
    {
        $this->view = $path;
    }

    public function getViewPath()
    {
        return $this->view;
    }

    public function setError($message)
    {
        $this->errorMessage = $message;
    }

    public function getError()
    {
        return $this->errorMessage;
    }
}
