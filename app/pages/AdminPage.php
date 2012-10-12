<?php

require_once 'Page.php';
require_once 'app/models/PostFactory.php';

class AdminPage extends Page {

    public function getEditorCode($id) {
        $post = PostFactory::getPost($id);
        $postData = array(
            'id'    => $post->getId(),
            'title' => $post->getTitle()
        );
        $this->appendDataToTemplate(array('post' => $postData));
        $this->displayTemplate('forms/editor.twig');
    }

    public function editPost($id) {
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

    public function deletePost() {
        if(!$this->getSlim()->request()->isAjax() || !Application::isAdmin()) {
            $this->getSlim()->error('404');
        }
        $id = func_get_arg(0);
        $id = $id[0];
        try {
            PostFactory::deletePost($id);
        }
        catch(Exception $e) {
            echo json_encode(array('error' => $e->getMessage()));
        }
    }
}
