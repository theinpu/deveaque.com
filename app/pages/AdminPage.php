<?php

require_once 'Page.php';
require_once 'app/models/PostFactory.php';

class AdminPage extends Page {

    public function showUpload() {
        $this->getSlim()->view()->display('upload.twig');
    }

}
