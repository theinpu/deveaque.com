<?php

require_once 'app/pages/Page.php';
require_once 'app/models/PostFactory.php';

class EditorsHandler extends Page {

    public function getPostEditor($id) {
        $post = PostFactory::getPost($id);
        $postData = array(
            'id'    => $post->getId(),
            'title' => $post->getTitle(),
            'photographer' => $post->getPhotographer()
        );

        $this->appendDataToTemplate(array('post' => $postData));
        $this->displayTemplate('forms/editor.twig');
    }

    public function getTagEditor($id) {
        $this->appendDataToTemplate(array('postId' => $id));
        $this->displayTemplate('forms/tagEditor.twig');
    }
}
