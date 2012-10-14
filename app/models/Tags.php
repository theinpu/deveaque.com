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
            $result[] = $tag['title'];
        }
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

    public static function attachPost($tagTitle, $postId) {
        self::checkCollections();

        $tagId = self::getTagIdByTitle($tagTitle);

        $link = self::$linkCollection->findOne(array('tagId' => $tagId, 'postId' => $postId));
        if(is_null($link)) {
            self::$linkCollection->save(array('tagId' => $tagId, 'postId' => $postId));
        } else {
            throw new InvalidArgumentException();
        }
    }

    private static function getTagIdByTitle($tagTitle) {
        $tagId = self::$tagCollection->findOne(array('title' => $tagTitle));
        if (is_null($tagId)) throw new InvalidArgumentException();
        return $tagId['_id']->{'$id'};
    }

    public static function deattachPost($tagTitle, $postId) {
        self::checkCollections();

        $tagId = self::getTagIdByTitle($tagTitle);
        $link = self::$linkCollection->findOne(array('tagId' => $tagId, 'postId' => $postId));
        if(!is_null($link)) {
            self::$linkCollection->remove(array('_id' => $link['_id']));
        } else {
            throw new InvalidArgumentException();
        }
    }

    public static function getAttachedPosts($tagTitle) {
        self::checkCollections();
        $tagId = self::getTagIdByTitle($tagTitle);
        $cursor = self::$linkCollection->find(array('tagId' => $tagId));
        $posts = array();
        while($cursor->hasNext()) {
            $post = $cursor->getNext();
            $posts[] = new MongoId($post['postId']);
        }
        return $posts;
    }
}
