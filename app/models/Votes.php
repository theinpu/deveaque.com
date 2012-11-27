<?php

require_once 'libs/MongoAssist.php';

class Votes {

    /**
     * @var MongoCollection
     */
    private static $votes = null;

    public static function getRating($postId) {
        self::checkCollecktion();
        $count = self::$votes->count(array('post' => $postId, 'dir' => 'asc'));
        $count -= self::$votes->count(array('post' => $postId, 'dir' => 'desc'));

        return $count;
    }

    public static function rateUp($postId, $userId) {
        self::checkCollecktion();
        if(self::$votes->count(array('post' => $postId, 'user' => $userId)) == 0) {
            self::$votes->insert(array('post' => $postId, 'user' => $userId, 'dir' => 'asc'));
        }
    }

    public static function rateDown($postId, $userId) {
        self::checkCollecktion();
        if(self::$votes->count(array('post' => $postId, 'user' => $userId)) == 0) {
            self::$votes->insert(array('post' => $postId, 'user' => $userId, 'dir' => 'desc'));
        }
    }

    public static function isVoted($postId, $userId) {
        self::checkCollecktion();

        return self::$votes->count(array('post' => $postId, 'user' => $userId)) != 0;
    }

    private static function checkCollecktion() {
        if(is_null(self::$votes)) {
            self::$votes = MongoAssist::GetCollection('votes');
        }
    }

}
