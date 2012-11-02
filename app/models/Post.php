<?php

class Post {

    private $data;

    public function __construct($data) {
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

}
