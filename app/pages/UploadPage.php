<?php

class UploadPage extends Page {

    private $uploadPath;

    public function __construct($slim) {
        parent::__construct($slim);
        $this->uploadPath = $_SERVER['DOCUMENT_ROOT'].'/../upload/';
    }

    public function index() {
        $this->getSlim()->view()->display('upload.html');
    }

    public function uploadImages() {
        $files = $_FILES;
        foreach($files['image']['tmp_name'] as $file) {
            $date = date('U');
            $fileName = $this->uploadFile($file);
            MongoAssist::GetCollection('posts')->insert(array('date' => $date, 'file' => $fileName));
        }

        $this->getSlim()->redirect('/');
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
