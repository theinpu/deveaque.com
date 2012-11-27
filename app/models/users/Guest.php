<?php

require_once 'GenericUser.php';

class Guest implements GenericUser {

    public function isGuest() {
        return true;
    }

    public function getData() {
        return array();
    }
}
