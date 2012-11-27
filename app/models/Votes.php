<?php

require_once 'libs/MongoAssist.php';

class Votes {

    public static function getRating($postId) {
        return 0;
    }

    public static function rateUp($postId, $userId) {

    }

    public static function rateDown($postId, $userId) {

    }

    public static function isVoted($postId, $userId) {
        return false;
    }

}
