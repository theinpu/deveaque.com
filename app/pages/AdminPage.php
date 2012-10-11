<?php

require_once 'Page.php';
require_once 'app/models/Post.php';

class AdminPage extends Page {

    public function deletePost() {
        if(!$this->getSlim()->request()->isAjax() || !Application::isAdmin()) {
            $this->getSlim()->error('404');
        }
        $id = func_get_arg(0);
        $id = $id[0];
        try {
            Post::deletePost($id);
        }
        catch(Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
}
