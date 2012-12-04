<?php

require_once 'app/models/PostFactory.php';
require_once 'app/models/Tags.php';
require_once 'app/pages/ContentHandler.php';
require_once 'app/models/Votes.php';

class MainSitePages extends Section {

    const PostPerPage = 20;

    public function showDefault($page = -1) {
        $pages = ceil(PostFactory::getCount() / self::PostPerPage);
        $page = $this->setupPage($page, $pages);
        $posts = $this->loadPosts(($page - 1) * self::PostPerPage, self::PostPerPage);

        $preload = '/';
        if($page < $pages) {
            $preload = '/page'.($pages - $page);
        }
        $this->appendDataToTemplate(array(
                                         'posts'       => $posts,
                                         'page'        => $page,
                                         'pages'       => $pages,
                                         'preloadPage' => $preload,
                                    ));
        $this->displayTemplate('main.twig');
    }

    private function setupPage($page, $pages) {
        if($page == -1) {
            $page = $pages;
        }
        $page = $pages - $page + 1;
        if($page < 0) {
            $this->getSlim()->notFound();
        }

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

        $preload = '/';
        if($page < $pages) {
            $preload = '/post/'.$title.'/page'.($pages - $page);
        }

        $posts = $this->loadPostsByTitle($title, ($page - 1) * self::PostPerPage);
        $this->appendDataToTemplate(array(
                                         'posts'       => $posts,
                                         'page'        => $page,
                                         'pages'       => $pages,
                                         'preloadPage' => $preload,
                                         'baseLink'    => '/post/'.$title
                                    ));
        $this->displayTemplate('main.twig');
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

        $preload = '/';
        if($page < $pages) {
            $preload = '/tag/'.$tag.'/page'.($pages - $page);
        }

        $postsList = PostFactory::getPostsByIds($postIds, ($page - 1) * self::PostPerPage, self::PostPerPage);
        $posts = $this->buildPosts($postsList);
        $this->appendDataToTemplate(array(
                                         'posts'       => $posts,
                                         'page'        => $page,
                                         'pages'       => $pages,
                                         'preloadPage' => $preload,
                                         'baseLink'    => '/tag/'.$tag
                                    ));
        $this->displayTemplate('main.twig');
    }

    private function buildPosts($postsList) {
        $posts = array();
        foreach($postsList as $item) {
            $size = $item->getSize();
            $zoomable = ($size[0] > ContentHandler::PreviewRecangle || $size[1] > ContentHandler::PreviewRecangle);
            if(is_null($item->getRating())) {
                $item->setRating(Votes::getRating($item->getId()));
                PostFactory::savePost($item);
            }
            $post = array(
                'id'           => $item->getId(),
                'title'        => $item->getTitle(),
                'tmb'          => $item->getSmallImage(),
                'image'        => $item->getFullImage(),
                'date'         => date('Y-m-d H:i:s', $item->getDate()),
                'tags'         => Tags::getItemList($item->getId()),
                'photographer' => $item->getPhotographer(),
                'object'       => $item,
                'zoomable'     => $zoomable,
                'rating'       => $item->getRating(),
                'canVote'      => !Votes::isVoted($item->getId(), Users::getCurrentUser()->getId()),
            );
            $posts[] = $post;
        }

        return $posts;
    }

    public function showPost($postId) {
        try {
            $post = PostFactory::getPost($postId);
        }
        catch(InvalidArgumentException $e) {
            $this->getSlim()->notFound();
        }
        $formattedDateLine = date('d F Y H:i', $post->getDate());
        $postTitle = $post->getTitle();
        $title = (!empty($postTitle)) ?
            $postTitle.' - posted @ '.$formattedDateLine :
            'Post from '.$formattedDateLine;
        $this->getSlim()->view()->setData('siteTitle', $title.' - '.Application::Title);
        $post = $this->buildPosts(array($post));

        $preload = '/';

        $this->appendDataToTemplate(array(
                                         'item'        => $post[0],
                                         'preloadPage' => $preload,
                                    ));
        $this->displayTemplate('singlePost.twig');
    }

    public function searchTag() {
        $term = $this->getSlim()->request()->get('term');
        $tags = Tags::searchTags($term);
        echo json_encode($tags);
    }

    public function showBest($page = -1) {
        $pages = ceil(PostFactory::getCount() / self::PostPerPage);
        $page = $this->setupPage($page, $pages);
        $posts = $this->loadPostsByRating(($page - 1) * self::PostPerPage, self::PostPerPage);

        $preload = '/';
        if($page < $pages) {
            $preload = '/best/page'.($pages - $page);
        }
        $this->appendDataToTemplate(array(
                                         'posts'       => $posts,
                                         'page'        => $page,
                                         'pages'       => $pages,
                                         'preloadPage' => $preload,
                                         'baseLink'    => '/best',
                                    ));
        $this->displayTemplate('main.twig');
    }

    private function loadPostsByRating($offset) {
        $postsList = PostFactory::getPostsByRating($offset, self::PostPerPage);

        return $this->buildPosts($postsList);
    }
}
