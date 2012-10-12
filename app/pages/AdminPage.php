<?php

require_once 'Page.php';
require_once 'app/models/Post.php';

class AdminPage extends Page {

    public function getEditorCode($id) {
        $post = Post::getPost($id);
        $postData = array(
            'id'    => $post->getId(),
            'title' => $post->getTitle()
        );
        $this->appendDataToTemplate(array('post' => $postData));
        $this->displayTemplate('forms/editor.twig');
    }

    public function editPost($id) {
        try {
            $post = Post::getPost($id);
            $post->setTitle($this->getSlim()->request()->post('title'));
            echo json_encode(array('saved' => true, 'title' => $post->getTitle()));
        }
        catch(Exception $ex) {
            echo json_encode(array('saved' => false));
        }
    }

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
