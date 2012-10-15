<?php

class EditorsHandler extends Page {

    public function getPostEditor($id) {
        $post = PostFactory::getPost($id);
        $postData = array(
            'id'    => $post->getId(),
            'title' => $post->getTitle()
        );

        $this->appendDataToTemplate(array('post' => $postData));
        $this->displayTemplate('forms/editor.twig');
    }

    public function getTagEditor($id) {
        $this->appendDataToTemplate(array('postId' => $id));
        $this->displayTemplate('forms/tagEditor.twig');
    }
}
