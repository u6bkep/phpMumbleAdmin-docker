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
* Ice connection class.
* Based for php-Ice 3.4 and superior.
*/

class PMA_MurmurConnectionIceException extends PMA_Exception {}

class PMA_MurmurConnectionIce extends PMA_debugSubject
{
    /*
    * ICE proxy interface.
    */
    protected $ICE;
    /*
    * Current php_Ice_module int version
    */
    protected $phpIceVersion;
    /*
    * Is php_Ice_module version supported by PMA ?
    * By default, no.
    */
    protected $isPhpIceVersionSupported = false;
    /*
    * Maximum php_Ice_module int version supported by PMA.
    */
    protected $phpIceVersion_max;
    /*
    * Murmur_Meta proxy interface.
    */
    protected $meta;
    /*
    * Murmur version array.
    */
    public $version = array();
    /*
    * Murmur default configuration array.
    */
    public $defaultConf = array();
    /*
    * Connection parameters.
    */
    protected $host = '127.0.0.1';
    protected $port = 6502;
    protected $timeout = 10;
    protected $secret = array();
    protected $slice_php_file_path = '';
    protected $slice_php_custom_path = '';

    /*
    * Send a debug message.
    */
    protected function debug($text, $level = 2, $error = false)
    {
        parent::debug(__class__ .' '.$text, $level, $error);
    }

    /*
    * Throw PMA Exception
    * $error can also be the exception object.
    */
    protected function throwException($error)
    {
        // Exception object.
        if (is_object($error)) {
            $array = pmaGetExceptionClass($error);
            $error = $this->getExceptionKey($array['class'], $array['text']);
            $this->debugError($array['class']);
        } else {
            $this->debugError($error);
        }
        throw new PMA_MurmurConnectionIceException($error);
    }

    protected function getExceptionKey($class, $text)
    {
        switch($class) {
            case 'Ice_EndpointParseException':
                if (false !== stripos($text, 'no argument provided')) {
                    $key = 'missing_argument';
                } elseif (false !== stripos($text, 'invalid port value')) {
                    $key = 'invalid_port';
                } else {
                    $key = 'Ice_UnknownErrorException';
                }
                break;
            case 'Ice_DNSException':
                $key = 'Ice_DNSException';
                break;
            case 'Ice_ConnectionRefusedException':
                $key = 'Ice_ConnectionRefusedException';
                break;
            case 'Ice_ConnectTimeoutException':
                $key = 'Ice_ConnectTimeoutException';
                break;
            case 'Murmur_InvalidSecretException':
                $key = 'Murmur_InvalidSecretException';
                break;
            default:
                $key = 'Ice_UnknownErrorException';
                break;
        }
        return $key;
    }

    public function setIcePhpVersion($version)
    {
        $this->phpIceVersion = $version;
    }

    public function isIcePhpVersionSupported($bool)
    {
        $this->isPhpIceVersionSupported = $bool;
    }

    public function setHost($host)
    {
        $this->host = $host;
    }

    public function setPort($port)
    {
        $this->port = $port;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function setSecret($secret)
    {
        if (is_string($secret) && $secret !== '') {
            $this->secret = array('secret' => $secret);
        }
    }

    public function setSlicePhpPaths($filePath, $customPath)
    {
        $this->slice_php_file_path = $filePath;
        $this->slice_php_custom_path = $customPath;
    }

    public function getMeta()
    {
        return $this->meta;
    }

    /*
    * Connection sequence.
    */
    public function connect()
    {
        $this->debug('initialize');
        $this->initialize();
        $this->debug('loadSlicesDefinitions');
        $this->loadSlicesDefinitions();
        $this->debug('checkSlicesDefinitions');
        $this->checkSlicesDefinitions();
        $this->debug('initalizeIce (get $ICE)');
        $this->initalizeIce();
        $this->debug('connection ('.$this->host.':'.$this->port.')');
        $this->getProxy();
        $this->debug('getMurmurDefaultConf');
        $this->getMurmurDefaultConf();
        $this->debug('getMurmurVersion');
        $this->getMurmurVersion();
        $this->debug('Connected :)');
        return $this->meta;
    }

    protected function initialize()
    {
        if (! extension_loaded('ice')) {
            $this->throwException('ice_module_not_found');
        }
        if (! $this->isPhpIceVersionSupported) {
            $this->throwException('phpIce_version_not_supported');
        }
        /*
        * Theses invalid parameters return an "EndpointParseException".
        * Prevent it and send a specific error message.
        */
        if ($this->host === '') {
            $this->throwException('Ice_DNSException');
        }
        if (! is_int($this->timeout) OR $this->timeout <= 0) {
            $this->throwException('invalid_numerical');
        }
        /*
        * Ice timeout is in millisecondes.
        *
        * Zeroc ice use an automatic retries function if a timeout expired.
        * By default, it's configured for 1 retry.
        * see: https://doc.zeroc.com/display/Ice36/Automatic+Retries
        *
        * WORKAROUND:
        * Divide by 2 the timeout parameter to feet with user selection.
        */
        $this->timeout = $this->timeout * 500;
    }

    /*
    * php-Ice 3.4 require a workaround to load Ice.php and slices definitions.
    * see includes/ice34Workaround.inc
    */
    protected function loadSlicesDefinitions()
    {
        // Do nothing.
    }

    protected function checkSlicesDefinitions()
    {
        // Check if ice.php file have been loaded.
        if (! interface_exists('Ice_Object')) {
            $this->throwException('ice_could_not_load_Icephp_file');
        }
        // Check if slices definitions file have been loaded.
        if (! interface_exists('Murmur_Meta')) {
            $this->throwException('ice_no_slice_definition_found');
        }
        // slice file is invalid.
        // Check if new methods included before 1.2.3 are available.
        $methods[] = 'getUsers';
        $methods[] = 'getUptime';
        $methods[] = 'getCertificateList';
        $methods[] = 'getLogLen';
        foreach ($methods as $method) {
            if (! method_exists('Murmur_Server', $method)) {
                $this->throwException('ice_invalid_slice_file');
                break;
            }
        }
    }

    protected function initalizeIce()
    {
        try {
            $this->ICE = Ice_initialize();
        } catch (Exception $e) {
            $this->throwException($e);
        }
    }

    protected function getProxy()
    {
        try {
            $proxy = $this->ICE->stringToProxy('Meta:tcp -h '.$this->host.' -p '.$this->port.' -t '.$this->timeout);
            $this->meta = $proxy->ice_checkedCast('::Murmur::Meta')->ice_context($this->secret);
        } catch (Exception $e) {
            $this->throwException($e);
        }
    }

    /*
    * Get Murmur default configuration
    * and check for a valid readsecret
    */
    protected function getMurmurDefaultConf()
    {
        try {
            $this->defaultConf = $this->meta->getDefaultConf();
        } catch (Exception $e) {
            $this->throwException($e);
        }
    }

    /*
    * Get murmur version.
    */
    protected function getMurmurVersion()
    {
        try {
            $this->meta->getVersion($a, $b, $c, $d);
        } catch (Exception $e) {
            $this->throwException($e);
        }
        $this->version['int'] = intval($a.$b.$c);
        $this->version['str'] = $a.'.'.$b.'.'.$c;
        $this->version['full'] = $this->version['str'];
        if ($d !== '' && $d !== $this->version['str']) {
            $this->version['full'] .= ' - '.$d;
        }
        /*
        * PMA works for murmur 1.2.3 and superior only.
        */
        if ($this->version['int'] < 123) {
            $this->throwException('ice_invalid_murmur_version');
        }
    }
}
