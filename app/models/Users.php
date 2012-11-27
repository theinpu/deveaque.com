<?php

require_once 'users/User.php';
require_once 'users/Guest.php';

class Users {

    private static $collection = null;
    /**
     * @var User
     */
    private static $user = null;

    private static function setCollection() {
        self::$collection = MongoAssist::GetCollection('users');
    }

    public static function getCurrentUser() {
        self::$user = new Guest();
    }
}
