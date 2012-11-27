<?php

require_once 'users/User.php';
require_once 'users/Guest.php';

class Users {

    /**
     * @var MongoCollection
     */
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

    public static function registerUser($email, $pass) {
        $userInfo = self::$collection->findOne(array('email' => $email));
        if(!($userInfo)) {
            throw new InvalidArgumentException('user exists');
        }

        $user = new User($email, $pass);
        self::$collection->save($user->getData());
        self::$user = $user;
    }
}
