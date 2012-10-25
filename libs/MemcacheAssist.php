<?php

class MemcacheAssist {

    /**
     * @var Memcache
     */
    private static $mmc = null;

    public static function getValue($key) {
        self::connectToMemcache();

        return self::$mmc->get($key);
    }

    public static function setValue($key, $value) {
        self::connectToMemcache();
        self::$mmc->set($key, $value, 80399);
    }

    public static function deleteValue($key) {
        self::connectToMemcache();
        self::$mmc->delete($key);
    }

    public static function isExists($key) {
        return self::$mmc->get($key) !== false;
    }

    private static function connectToMemcache() {
        if(is_null(self::$mmc)) {
            self::$mmc = new Memcache();
            self::$mmc->connect('unix:///tmp/memcached.sock', 0);
        }
    }

}
