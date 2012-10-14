<?php

require_once 'app/models/PostFactory.php';
require_once 'app/models/Tags.php';

class MainPage extends Page {

    const PostPerPage = 20;

    public function index($page = 1) {
        $posts = $this->loadPosts(($page - 1) * self::PostPerPage, self::PostPerPage);
        $pages = ceil(PostFactory::getCount() / self::PostPerPage);
        $this->appendDataToTemplate(array(
            'posts' => $posts,
            'page' => $page,
            'pages' => $pages));
        $this->displayTemplate('main.twig');
    }

    private function loadPosts($offset) {
        $postsList = PostFactory::getPosts($offset, self::PostPerPage);
        return $this->buildPosts($postsList);
    }

    public function showFullImage() {
        $args = func_get_arg(0);
        $basePath = $args[0] . '/' . $args[1] . '/' . $args[2] . '/' . $args[3];
        $sourcePath = '../upload/' . $basePath;
        $destPath = 'image/full/' . $basePath;
        if (!file_exists($destPath)) {
            @mkdir(dirname($destPath), 0777, true);
            @copy($sourcePath, $destPath);
        }
        $this->getSlim()->response()->header('Content-Type', 'image/jpeg');
        echo file_get_contents($sourcePath);
    }

    public function showSmallImage() {
        $args = func_get_arg(0);
        $basePath = $args[0] . '/' . $args[1] . '/' . $args[2] . '/' . $args[3];
        $sourcePath = '../upload/' . $basePath;
        $destPath = 'image/small/' . $basePath;
        if (!file_exists($destPath)) {
            @mkdir(dirname($destPath), 0777, true);
            $sourceImage = imagecreatefromjpeg($sourcePath);
            $sizes = getimagesize($sourcePath);
            if ($sizes[1] > 650) {
                $height = 650;
                $width = ($height / $sizes[1]) * $sizes[0];
            } else {
                $width = $sizes[0];
                $height = $sizes[1];
            }
            $destImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($destImage, $sourceImage, 0, 0, 0, 0, $width, $height, $sizes[0], $sizes[1]);
            imagedestroy($sourceImage);
            imagejpeg($destImage, $destPath, 90);
            imagedestroy($destImage);
        }
        $this->getSlim()->response()->header('Content-Type', 'image/jpeg');
        echo file_get_contents($destPath);
    }

    public function showByTitle($title, $page = 1) {

        $this->getSlim()->view()->setData('siteTitle', $title.' - '.Application::Title);

        $posts = $this->loadPostsByTitle($title, ($page - 1) * self::PostPerPage);
        $pages = ceil(PostFactory::getCount(array('title' => $title)) / self::PostPerPage);
        $this->appendDataToTemplate(array(
            'posts' => $posts,
            'page' => $page,
            'pages' => $pages,
            'baseLink' => '/post/' . $title
        ));
        $this->displayTemplate('main.twig');
    }

    private function loadPostsByTitle($title, $offset) {
        $postsList = PostFactory::getPostsByTitle($title, $offset, self::PostPerPage);
        return $this->buildPosts($postsList);
    }

    private function buildPosts($postsList) {
        $posts = array();
        foreach ($postsList as $item) {
            $post = array(
                'id' => $item->getId(),
                'title' => $item->getTitle(),
                'tmb' => $item->getSmallImage(),
                'image' => $item->getFullImage(),
                'date' => date('Y-m-d H:i:s', $item->getDate()),
                'tags' => Tags::getItemList($item->getId())
            );
            $posts[] = $post;
        }
        return $posts;
    }

    public function showByTag($tag, $page = 1) {
        $this->getSlim()->view()->setData('siteTitle', $tag.' - '.Application::Title);

        $postIds = Tags::getAttachedPosts($tag);
        $postsList = PostFactory::getPostsByIds($postIds, ($page - 1) * self::PostPerPage, self::PostPerPage);
        $posts = $this->buildPosts($postsList);
        $pages = ceil(count($postIds) / self::PostPerPage);
        $this->appendDataToTemplate(array(
            'posts' => $posts,
            'page' => $page,
            'pages' => $pages,
            'baseLink' => '/tag/' . $tag
        ));
        $this->displayTemplate('main.twig');
    }

}