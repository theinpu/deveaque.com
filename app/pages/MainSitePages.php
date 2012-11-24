<?php

require_once 'app/models/PostFactory.php';
require_once 'app/models/Tags.php';
require_once 'app/pages/ContentHandler.php';

class MainSitePages extends Page {

    const PostPerPage = 2;

    public function showDefault($page = -1) {
        $pages = ceil(PostFactory::getCount() / self::PostPerPage);
        $page = $this->setupPage($page, $pages);
        $posts = $this->loadPosts(($page - 1) * self::PostPerPage, self::PostPerPage);
        $this->appendDataToTemplate(array(
                                         'posts' => $posts,
                                         'page'  => $page,
                                         'pages' => $pages));
        $this->displayTemplate('main.twig');
        $this->getSlim()->lastModified((int)$posts[0]['object']->getDate());
    }

    private function setupPage($page, $pages) {
        if($page == -1) {
            $page = $pages;
        }
        $page = $pages - $page + 1;

        return $page;
    }

    private function loadPosts($offset) {
        $postsList = PostFactory::getPosts($offset, self::PostPerPage);

        return $this->buildPosts($postsList);
    }

    public function showByTitle($title, $page = -1) {
        $this->getSlim()->view()->setData('siteTitle', $title.' - '.Application::Title);
        $pages = ceil(PostFactory::getCount(array('title' => $title)) / self::PostPerPage);
        $page = $this->setupPage($page, $pages);

        $posts = $this->loadPostsByTitle($title, ($page - 1) * self::PostPerPage);
        $this->appendDataToTemplate(array(
                                         'posts'    => $posts,
                                         'page'     => $page,
                                         'pages'    => $pages,
                                         'baseLink' => '/post/'.$title
                                    ));
        $this->displayTemplate('main.twig');
        $this->getSlim()->lastModified((int)$posts[0]['object']->getDate());
    }

    private function loadPostsByTitle($title, $offset) {
        $postsList = PostFactory::getPostsByTitle($title, $offset, self::PostPerPage);

        return $this->buildPosts($postsList);
    }

    public function showByTag($tag, $page = -1) {
        $this->getSlim()->view()->setData('siteTitle', $tag.' - '.Application::Title);
        $postIds = Tags::getAttachedPosts($tag);
        $pages = ceil(count($postIds) / self::PostPerPage);
        $page = $this->setupPage($page, $pages);

        $postsList = PostFactory::getPostsByIds($postIds, ($page - 1) * self::PostPerPage, self::PostPerPage);
        $posts = $this->buildPosts($postsList);
        $this->appendDataToTemplate(array(
                                         'posts'    => $posts,
                                         'page'     => $page,
                                         'pages'    => $pages,
                                         'baseLink' => '/tag/'.$tag
                                    ));
        $this->displayTemplate('main.twig');
        $this->getSlim()->lastModified((int)$posts[0]['object']->getDate());
    }

    private function buildPosts($postsList) {
        $posts = array();
        foreach($postsList as $item) {
            $size = $item->getSize();
            $zoomable = ($size[0] > ContentHandler::PreviewRecangle || $size[1] > ContentHandler::PreviewRecangle);
            $post = array(
                'id'           => $item->getId(),
                'title'        => $item->getTitle(),
                'tmb'          => $item->getSmallImage(),
                'image'        => $item->getFullImage(),
                'date'         => date('Y-m-d H:i:s', $item->getDate()),
                'tags'         => Tags::getItemList($item->getId()),
                'photographer' => $item->getPhotographer(),
                'object'       => $item,
                'zoomable'     => $zoomable
            );
            $posts[] = $post;
        }

        return $posts;
    }

    public function showPost($postId) {
        $post = PostFactory::getPost($postId);
        $formattedDateLine = date('d F Y H:i', $post->getDate());
        $postTitle = $post->getTitle();
        $title = (!empty($postTitle)) ?
            $postTitle.' - posted @ '.$formattedDateLine :
            'Post from '.$formattedDateLine;
        $this->getSlim()->view()->setData('siteTitle', $title.' - '.Application::Title);
        $post = $this->buildPosts(array($post));
        $this->appendDataToTemplate(array(
                                         'item' => $post[0],
                                    ));
        $this->displayTemplate('singlePost.twig');
        $this->getSlim()->lastModified((int)$post[0]['object']->getDate());
    }

    public function searchTag() {
        $term = $this->getSlim()->request()->get('term');
        $tags = Tags::searchTags($term);
        echo json_encode($tags);
    }
}
