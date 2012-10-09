<?php

class UploadPage extends Page {

    public function index() {
        $this->getSlim()->view()->display('upload.html');
    }

}
