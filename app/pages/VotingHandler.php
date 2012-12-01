<?php

require_once 'app/models/PostFactory.php';
require_once 'app/models/Users.php';
require_once 'app/models/Votes.php';

class VotingHandler extends Page {

    public function rateUp($postId) {
        if(!$this->getSlim()->request()->isAjax()) {
            $this->getSlim()->redirect('/');

            return;
        }

        $user = Users::getCurrentUser();
        if($user->isGuest()) {
            echo json_encode(array(
                                  'msg'   => 'unregistered user',
                                  'error' => true));

            return;
        }

        if(is_null(PostFactory::getPost($postId))) {
            echo json_encode(array(
                                  'msg'   => 'unknown post',
                                  'error' => true));

            return;
        }

        Votes::rateUp($postId, $user->getId());
        $this->sendRating($postId);
    }

    private function sendRating($postId) {
        echo json_encode(array(
                              'rating' => Votes::getRating($postId),
                              'error'  => false
                         ));
    }

    public function rateDown($postId) {
        if(!$this->getSlim()->request()->isAjax()) {
            $this->getSlim()->redirect('/');

            return;
        }

        $user = Users::getCurrentUser();
        if($user->isGuest()) {
            echo json_encode(array(
                                  'msg'   => 'unregistered user',
                                  'error' => true));

            return;
        }

        if(is_null(PostFactory::getPost($postId))) {
            echo json_encode(array(
                                  'msg'   => 'unknown post',
                                  'error' => true));

            return;
        }

        Votes::rateDown($postId, $user->getId());
        $this->sendRating($postId);
    }

}
