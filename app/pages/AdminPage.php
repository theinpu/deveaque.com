<?php

require_once 'Section.php';
require_once 'app/models/PostFactory.php';

class AdminSection extends Section {

    public function showUpload() {
        $this->getSlim()->view()->display('upload.twig');
    }

}
