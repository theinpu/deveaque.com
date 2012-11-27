<?php

require_once 'GenericUser.php';

class User implements GenericUser {

    private $userInfo;

    public function __construct($userInfo) {
        $this->userInfo = $userInfo;
    }

    public function isGuest() {
        return false;
    }

    public function getData() {
        return $this->userInfo;
    }

    public function getId() {
        if(isset($this->userInfo['_id'])) {
            return $this->userInfo['_id']->{'$id'};
        }

        return null;
    }
}
