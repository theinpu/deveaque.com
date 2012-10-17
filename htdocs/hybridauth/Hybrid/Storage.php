<?php
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html 
*/

/**
 * HybridAuth storage manager
 */

require_once 'libs/MemcacheAssist.php';

class Hybrid_Storage {

    function __construct() {
        if(!session_id()) {
            if(!session_start()) {
                throw new Exception("Hybridauth requires the use of 'session_start()' at the start of your script, which appears to be disabled.", 1);
            }
        }

        $this->config("php_session_id", session_id());
        $this->config("version", Hybrid_Auth::$version);
    }

    public function config($key, $value = null) {
        $key = strtolower($key);

        $config = $this->getConfig();

        if($value) {
            $config[$key] = $value;
        }
        elseif(isset($config[$key])) {
            return $config[$key];
        }

        $this->saveConfig($config);

        return null;
    }

    private function saveConfig($config) {
        MemcacheAssist::setValue("HA_CONFIG", $config);
    }

    private function getConfig() {
        return MemcacheAssist::getValue("HA_CONFIG");
    }

    public function get($key) {
        $key = strtolower($key);

        $store = $this->getStore();

        if(isset($store[$key])) {
            return $store[$key];
        }

        return null;
    }

    private function saveStore($store) {
        MemcacheAssist::setValue('HA_STORE', $store);
    }

    private function getStore() {
        return MemcacheAssist::getValue("HA_STORE");
    }

    public function set($key, $value) {
        $key = strtolower($key);

        $store = $this->getStore();

        $store[$key] = $value;

        $this->saveStore($store);
    }

    function clear() {
        $this->saveStore(array());
    }

    function delete($key) {
        $key = strtolower($key);

        $store = $this->getStore();

        if(isset($store[$key])) {
            unset($store[$key]);
        }

        $this->saveStore($store);
    }

    function deleteMatch($key) {
        $key = strtolower($key);

        $store = $this->getStore();

        if(!empty($store)) {
            foreach($store as $k => $v) {
                if(strstr($k, $key)) {
                    unset($store[$k]);
                }
            }
        }

        $this->saveStore($store);
    }

    function getSessionData() {
        $store = $this->getStore();
        if(isset($store)) {
            return serialize($store);
        }

        return null;
    }

    function restoreSessionData($sessiondata = null) {
        $this->saveStore(unserialize($sessiondata));
    }
}
