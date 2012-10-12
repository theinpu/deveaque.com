<?php

class Post {

    private $data;

    /**
     * @var MongoCollection
     */
    private static $collection = null;

    public static function createPost($title, $file) {
        $date = date('U');
        self::setCollection();
        self::$collection
            ->insert(array('date' => $date, 'file' => $file, 'title' => $title));
    }

    private static function setCollection() {
        if(is_null(self::$collection)) {
            $collectionName = strpos($_SERVER['HTTP_HOST'], 'dev.') !== false ? 'posts_dev' : 'posts';
            self::$collection = MongoAssist::GetCollection($collectionName);
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

    public static function getPost($id) {
        self::setCollection();

        return new Post(self::$collection->findOne(array('_id' => new MongoId($id))));
    }

    private function getFile() {
        return $this->data['file'];
    }

    public function setTitle($title) {
        self::setCollection();
        $this->data['title'] = $title;
        self::$collection->save($this->data);
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

}
