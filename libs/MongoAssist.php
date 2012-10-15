<?php

class MongoAssist {

    const dbName = 'deveaque';

    /**
     * @var Mongo
     */
    private static $mongo = null;
    /**
     * @var MongoDB
     */
    private static $db = null;
    private static $collections = array();

    /**
     * @param $collection
     *
     * @return MongoCollection
     */
    public static function GetCollection($collection) {
        self::checkMongoConnection();
        $collection = self::detectDevelopCollection($collection);
        self::loadCollection($collection);

        return self::$collections[$collection];
    }

    private static function detectDevelopCollection($collection)
    {
        $collection = ($_SERVER['DEVELOP'] ? $collection . '_dev' : $collection);
        return $collection;
    }

    private static function loadCollection($collection) {
        if(!array_key_exists($collection, self::$collections)) {
            self::$collections[$collection] = self::$db->selectCollection($collection);
        }
    }

    private static function checkMongoConnection() {
        if(is_null(self::$mongo)) {
            self::$mongo = new Mongo();
        }
        if(is_null(self::$db)) {
            self::$db = self::$mongo->selectDB(self::dbName);
        }
    }

}
