<?php

class PostHandler extends Page {

    private $uploadPath;

    public function __construct($slim) {
        parent::__construct($slim);

        $this->uploadPath = $_SERVER['DOCUMENT_ROOT'].'/../upload/';
    }

    public function addPost() {
        $this->checkAjaxPermissions();
        $files = $_FILES;
        $titles = $this->getSlim()->request()->post('title');
        foreach($files['image']['tmp_name'] as $id => $file) {
            $title = $titles[$id];
            $fileName = $this->uploadFile($file);
            PostFactory::createPost($title, $fileName);
        }
        $this->getSlim()->redirect('/');
    }

    public function editPost($id) {
        $this->checkAjaxPermissions();
        try {
            $post = PostFactory::getPost($id);
            $post->setTitle($this->getSlim()->request()->post('title'));
            PostFactory::savePost($post);
            echo json_encode(array('saved' => true, 'title' => $post->getTitle()));
        }
        catch(Exception $ex) {
            echo json_encode(array('saved' => false, 'error' => $ex->getMessage()));
        }
    }

    public function deletePost($id) {
        $this->checkAjaxPermissions();
        try {
            PostFactory::deletePost($id);
        }
        catch(Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }

    private function uploadFile($file) {
        if(!file_exists($file)) {
            throw new RuntimeException('А картинку прикрепить?');
        }

        $datePath = date('Y').'/'.date('m').'/'.date('d').'/';
        $imageDir = $this->uploadPath.$datePath;
        @mkdir($imageDir, 0777, true);
        $fileName = md5($file).'.jpg';
        move_uploaded_file($file, $imageDir.$fileName);
        $fileName = $datePath.$fileName;

        return $fileName;
    }

}
