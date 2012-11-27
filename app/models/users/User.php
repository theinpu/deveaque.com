<?php

require_once 'GenericUser.php';

class User implements GenericUser {

    private $email;
    private $pass;

    public function __costructor($email, $pass) {
        $this->email = $email;
        $this->pass = md5($pass.'very secret solt');
    }

    public function isGuest() {
        return false;
    }

    public function getData() {
        return array('email' => $this->email);
    }
}
