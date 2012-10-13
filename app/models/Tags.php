<?php

class Tags {

    /**
     * @var MongoCollection
     */
    private static $linkCollection = null;

    /**
     * @var MongoCollection
     */
    private static $tagCollection = null;

    public static function getItemList($postId) {
        self::checkCollections();
        $tagLinks = self::$linkCollection->find(array('postId' => $postId));
        $result = array();
        while($tagLinks->hasNext()) {
            $tagLink = $tagLinks->getNext();
            $tag = self::$tagCollection->findOne(array('_id' => new MongoId($tagLink['tagId'])));
            $result[] = array(
                'title' => $tag['title'],
                'id'    => $tagLink['tagId']
            );
        }

        $result[] = array(
            'title' => 'test',
            'id'    => '123'
        );

        return $result;
    }

    private static function checkCollections() {
        if(is_null(self::$linkCollection)) self::$linkCollection = MongoAssist::GetCollection('posts_tags');
        if(is_null(self::$tagCollection)) self::$tagCollection = MongoAssist::GetCollection('tags');
    }

    public static function saveTag($tagTitle) {
        self::checkCollections();

        $tag = self::$tagCollection->findOne(array('title' => $tagTitle));
        if(is_null($tag)) {
            self::$tagCollection->save(array('title' => $tagTitle));
        }

    }
}
