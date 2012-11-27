<?php

require_once 'users/User.php';
require_once 'users/Guest.php';

class Users {

    private static $collection = null;

    private static function setCollection() {
        self::$collection = MongoAssist::GetCollection('users');
    }

    public static function getCurrentUser() {

    }
}
