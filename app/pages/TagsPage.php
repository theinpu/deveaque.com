<?php

require_once 'Page.php';
require_once 'app/models/Tags.php';

class TagsPage extends Page {

    public function saveTag() {
        $tagTitle = $this->getSlim()->request()->post('title');
        try {
            Tags::saveTag($tagTitle);
            $result = array('success' => true, 'tag' => $tagTitle);
        }
        catch (Exception $ex) {
            $result = array('success' => false, 'error' => $ex->getMessage());
        }
        echo json_encode($result);
    }

    public function attachTagToPost($tagTitle, $postId) {
        //echo json_encode(array('success' => false, $tagTitle, $postId));
        $result = array('success' => false);
        try {
            Tags::attachPost($tagTitle, $postId);
            $result = array('success' => true);
        } catch(Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        echo json_encode($result);
    }

    public function getTagsList() {

    }

}
