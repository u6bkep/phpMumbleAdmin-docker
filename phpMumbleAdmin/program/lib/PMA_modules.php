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

class PMA_modules
{
    /*
    * Modules absolute path.
    */
    protected $path = '';

    /*
    * Modules store.
    */
    protected $store = array();

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function setErrorFile($file)
    {
        $this->errorFile = $this->path.$file;
    }

    public function getList()
    {
        return $this->store;
    }

    /*
    * Add a new module.
    */
    public function newModule($id)
    {
        $path = $this->path.$id.'/'.$id;

        $obj = new stdClass();
        $obj->id = $id;
        $obj->classPath = $path.'.class.php';
        $obj->controllerPath = $path.'.php';
        $obj->viewPath = $path.'.view.php';
        $obj->datas = null;
        $this->store[] = $obj;

        /*
        * Include class file ASAP.
        */
        if (is_readable($obj->classPath)) {
            require_once($obj->classPath);
        }
    }

    public function saveDatas($id, $object)
    {
        foreach ($this->store as &$module) {
            if ($module->id === $id) {
                $module->datas = clone $object;
                break;
            }
        }
    }

    public function getControllerPath($id)
    {
        foreach ($this->store as $module) {
            if ($id === $module->id) {
                return $module->controllerPath;
            }
        }
    }

    public function getViewPath($id)
    {
        foreach ($this->store as $module) {
            if ($id === $module->id) {
                return $module->viewPath;
            }
        }
        return $this->errorFile;
    }

    public function getDatas($id)
    {
        foreach ($this->store as $module) {
            if ($id === $module->id) {
                return $module->datas;
            }
        }
    }

    public function disable($id)
    {
        foreach ($this->store as $key => $module) {
            if ($module->id === $id) {
                unset($this->store[$key]);
                break;
            }
        }
    }
}
