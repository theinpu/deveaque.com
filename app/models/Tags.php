<?php

class Tags {

    public static function getItemList($postId) {
        $tagLinks = MongoAssist::GetCollection('posts_tags')->find(array('postId' => $postId));
        $result = array();
        while($tagLinks->hasNext()) {
            $tagLink = $tagLinks->getNext();
            $tag = MongoAssist::GetCollection('tags')->findOne(array('_id' => new MongoId($tagLink['tagId'])));
            $result[] = array(
                'title' => $tag['title'],
                'id'    => $tagLink['tagId']
            );
        }

        $result[] = array(
            'title' => 'test',
            'id'    => '123'
        );

        return $result;
    }
}
