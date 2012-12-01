<?php

require_once 'Post.php';

class PostFactory {

    /**
     * @var MongoCollection
     */
    private static $collection = null;

    public static function createPost(Post $post) {
        self::setCollection();
        self::$collection
            ->insert($post->getData());
    }

    private static function setCollection() {
        if(is_null(self::$collection)) {
            self::$collection = MongoAssist::GetCollection('posts');
        }
    }

    public static function getPosts($offset, $limit) {
        self::setCollection();
        $cursor = self::$collection->find()
            ->sort(array('date' => -1))
            ->skip($offset)->limit($limit);

        $result = array();
        while($cursor->hasNext()) {
            $result[] = new Post($cursor->getNext());
        }

        return $result;
    }

    public static function getCount($query = array()) {
        self::setCollection();

        return self::$collection->count($query);
    }

    public static function deletePost($id) {
        self::setCollection();
        $post = self::getPost($id);
        self::deleteFiles($post);

        self::$collection->remove(array('_id' => new MongoId($id)));
    }

    private static function deleteFiles(Post $post) {
        $originFile = $_SERVER['DOCUMENT_ROOT'].'/../upload/'.$post->getFile();
        $fullFile = $_SERVER['DOCUMENT_ROOT'].$post->getFullImage();
        $smallFile = $_SERVER['DOCUMENT_ROOT'].$post->getSmallImage();

        @unlink($originFile);
        @unlink($fullFile);
        @unlink($smallFile);
    }

    /**
     * @param $id
     *
     * @return Post
     */
    public static function getPost($id) {
        self::setCollection();

        return new Post(self::$collection->findOne(array('_id' => new MongoId($id))));
    }

    public static function getPostsByTitle($title, $offset, $limit) {
        self::setCollection();
        $cursor = self::$collection->find(array('title' => $title))
            ->sort(array('date' => -1))
            ->skip($offset)->limit($limit);

        $result = array();
        while($cursor->hasNext()) {
            $result[] = new Post($cursor->getNext());
        }

        return $result;
    }

    public static function savePost(Post $post) {
        self::setCollection();
        self::$collection->save($post->getData());
    }

    public static function getPostsByIds($postIds, $offset, $limit) {
        self::setCollection();
        $cursor = self::$collection->find(array('_id' => array('$in' => $postIds)))
            ->sort(array('date' => -1))
            ->skip($offset)->limit($limit);

        $result = array();
        while($cursor->hasNext()) {
            $result[] = new Post($cursor->getNext());
        }

        return $result;
    }

    public static function getPostsByRating($offset, $limit) {
        self::setCollection();
        $cursor = self::$collection->find()
            ->sort(array('rating' => -1))
            ->skip($offset)->limit($limit);

        $result = array();
        while($cursor->hasNext()) {
            $result[] = new Post($cursor->getNext());
        }

        return $result;
    }

}
