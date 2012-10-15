<?php

require_once 'Page.php';
require_once 'app/models/Tags.php';

class TagHandler extends Page {

    public function saveTag() {
        $this->checkAjaxPermissions();
        $tagTitle = $this->getSlim()->request()->post('title');
        try {
            Tags::saveTag($tagTitle);
            $result = array('success' => true, 'tag' => $tagTitle);
        }
        catch(Exception $ex) {
            $result = array('success' => false, 'error' => $ex->getMessage());
        }
        echo json_encode($result);
    }

    public function attachTagToPost($tagTitle, $postId) {
        $this->checkAjaxPermissions();
        $result = array('success' => false);
        try {
            Tags::attachPost($tagTitle, $postId);
            $result = array('success' => true);
        }
        catch(Exception $ex) {
            $result['error'] = $ex->getMessage();
        }
        echo json_encode($result);
    }

    public function deattachTag($tagId, $postId) {
        $this->checkAjaxPermissions();
        try {
            Tags::deattachPost($tagId, $postId);
            $result = array('success' => true, 'tag' => $tagId, 'post' => $postId);
        }
        catch(Exception $ex) {
            $result = array('success' => false, 'error' => $ex->getMessage());
        }
        echo json_encode($result);
    }

}
