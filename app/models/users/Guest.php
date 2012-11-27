<?php

class Guest extends User {

    public function isGuest() {
        return true;
    }

}
