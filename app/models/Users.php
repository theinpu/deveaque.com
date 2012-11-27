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
        self::setCollection();
        self::autoLogin();

        return self::$user;
    }

    private static function autoLogin() {
        if(is_null(self::$user)) {
            $sesid = session_id();
            if(empty($sesid) || !isset($_SESSION['email'])) {
                self::$user = new Guest();
            }
            else {
                $email = $_SESSION['email'];
                $userInfo = self::$collection->findOne(array('email' => $email));
                self::$user = new User($userInfo);
            }
        }
    }

    public static function registerUser($email, $pass) {
        self::setCollection();
        $userInfo = self::$collection->findOne(array('email' => $email));
        if($userInfo) {
            throw new InvalidArgumentException('user exists');
        }

        $user = new User(
            array(
                 'email' => $email,
                 'pass'  => md5($pass.'very secret solt')
            )
        );
        self::$collection->save($user->getData());
        self::loginByPass($email, $pass);
    }

    public function loginByPass($email, $pass) {
        self::setCollection();
        $userInfo = self::$collection->findOne(array('email' => $email, 'pass' => md5($pass.'very secret solt')));
        if(!empty($userInfo)) {
            self::$user = new Guest();

            return;
        }

        self::$user = new User($userInfo);
    }
}
