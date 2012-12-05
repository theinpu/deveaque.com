<?php

require_once 'PostFactory.php';
require_once 'libs/MemcacheAssist.php';

class Page {

    const PostPerPage = 20;

    private $posts = array();
    /**
     * @var MongoId
     */
    private $id = null;
    private $pageNumber = 0;

    public function __construct($id = null, $pageData = array()) {
        $this->checkConstructParams($id, $pageData);
    }

    private function checkConstructParams($id, $pageData) {
        if($id == null && empty($pageData)) {
            throw new InvalidArgumentException();
        }
    }

    public function addPost($postId) {

    }

    public function removePost($postId) {

    }

    public function getPosts() {

    }

}
