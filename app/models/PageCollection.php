<?php

require_once 'libs/MongoAssist.php';
require_once 'Page.php';

class PageCollection {

    /**
     * @var MongoCollection
     */
    private static $pages = null;

    public static function getLastPage() {
        self::checkCollection();
        $pageData = self::$pages->findOne(array('posts' >= array('$lt' => Page::PostPerPage)));

        return new Page(null, $pageData);
    }

    private static function checkCollection() {
        if(is_null(self::$pages)) {
            self::$pages = MongoAssist::GetCollection('pages');
        }
    }

    public static function getPage($index) {
        self::checkCollection();
        $pageData = self::$pages->findOne(array('num' => $index));
    }

    public static function getPagesCount() {
        self::checkCollection();

        return self::$pages->count();
    }

}
