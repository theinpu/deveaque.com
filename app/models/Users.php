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
        self::autoLogin();

        return self::$user;
    }

    private static function autoLogin() {
        if(is_null(self::$user)) {
            self::$user = new Guest();
        }
    }
}
