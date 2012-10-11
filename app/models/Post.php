<?php

class Post {

    private $data;

    public static function createPost($title, $file) {
        $date = date('U');
        MongoAssist::GetCollection('posts')
            ->insert(array('date' => $date, 'file' => $file, 'title' => $title));
    }

    public static function getPosts($offset, $limit) {
        $cursor = MongoAssist::GetCollection('posts')->find()
            ->sort(array('date' => -1))
            ->skip($offset)->limit($limit);

        $result = array();
        while($cursor->hasNext()) {
            $result[] = new Post($cursor->getNext());
        }

        return $result;
    }

    private function __construct($data) {
        $this->data = $data;
    }

    public function getId() {
        return $this->data['_id']->{'$id'};
    }

    public function getTitle() {
        return $this->data['title'];
    }

    public function getDate() {
        return $this->data['date'];
    }

    public function getFullImage() {
        return '/image/full/'.$this->data['file'];
    }

    public function getSmallImage() {
        return '/image/small/'.$this->data['file'];
    }

    public static function getCount() {
        return MongoAssist::GetCollection('posts')->count();
    }

    public static function deletePost($id) {
        $post = self::getPost($id);
        self::deleteFiles($post);

        MongoAssist::GetCollection('posts')->remove(array('_id' => new MongoId($id)));
    }

    private static function deleteFiles(Post $post) {
        $originFile = $_SERVER['DOCUMENT_ROOT'].'/../upload/'.$post->getFile();
        $fullFile = $_SERVER['DOCUMENT_ROOT'].$post->getFullImage();
        $smallFile = $_SERVER['DOCUMENT_ROOT'].$post->getSmallImage();

        @unlink($originFile);
        @unlink($fullFile);
        @unlink($smallFile);
    }

    private static function getPost($id) {
        return new Post(MongoAssist::GetCollection('posts')->findOne(array('_id' => new MongoId($id))));
    }

    private function getFile() {
        return $this->data['file'];
    }

}
