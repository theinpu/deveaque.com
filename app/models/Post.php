<?php

class Post {

    private $data;

    public static function createPost($title, $file) {
        $date = date('U');
        MongoAssist::GetCollection('posts')->insert(array('date' => $date, 'file' => $file, 'title' => $title));
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

}
