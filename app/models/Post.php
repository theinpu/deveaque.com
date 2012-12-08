<?php

class Post {

    private $data;

    public function __construct($data = array()) {
        if(is_null($data)) {
            throw new InvalidArgumentException('post not found');
        }
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

    public function getFile() {
        return $this->data['file'];
    }

    public function setTitle($title) {
        $this->data['title'] = $title;
    }

    public function getPhotographer() {
        if(!isset($this->data['photographer'])) {
            return '';
        }

        return $this->data['photographer'];
    }

    public function setPhotographer($photographer) {
        $this->data['photographer'] = $photographer;
    }

    public function getData() {
        return $this->data;
    }

    public function getInfo() {
        if(!isset($this->data['info'])) {
            $file = dirname(__FILE__).'/../../upload/'.$this->getFile();
            $info = getimagesize($file);
            $this->data['info'] = $info;
            PostFactory::savePost($this);

            return $info;
        }

        return $this->data['info'];
    }

    public function getSize() {
        $info = $this->getInfo();
        if(!isset($this->data['size'])) {
            $size = array($info[0], $info[1]);
            $this->data['size'] = $size;
            PostFactory::savePost($this);

            return $size;
        }

        return $this->data['size'];
    }

    public function getRating() {
        if(!isset($this->data['rating'])) {
            return null;
        }

        return $this->data['rating'];
    }

    public function setRating($rating) {
        $this->data['rating'] = $rating;
    }

}
