<?php

require_once 'app/models/Post.php';

class MainPage extends Page {

    const PostPerPage = 20;

    public function index() {
        $page = func_get_arg(0);
        $page = empty($page) ? 1 : $page[0];
        $posts = $this->loadPosts(($page - 1) * self::PostPerPage, self::PostPerPage);
        $pages = ceil(Post::getCount() / self::PostPerPage);
        $this->getSlim()->view()->appendData(array(
                                                  'posts'     => $posts,
                                                  'page'      => $page,
                                                  'pages'     => $pages));
        $this->getSlim()->view()->display('main.twig');
    }

    private function loadPosts($offset, $limit) {
        $posts = array();
        $postsList = Post::getPosts($offset, $limit);
        foreach($postsList as $item) {
            $post = array(
                'id'    => $item->getId(),
                'title' => $item->getTitle(),
                'tmb'   => $item->getSmallImage(),
                'image' => $item->getFullImage(),
                'date'  => date('Y-m-d H:i:s', $item->getDate())
            );
            $posts[] = $post;
        }

        return $posts;
    }

    public function showFullImage() {
        $args = func_get_arg(0);
        $basePath = $args[0].'/'.$args[1].'/'.$args[2].'/'.$args[3];
        $sourcePath = '../upload/'.$basePath;
        $destPath = 'image/full/'.$basePath;
        if(!file_exists($destPath)) {
            @mkdir(dirname($destPath), 0777, true);
            @copy($sourcePath, $destPath);
        }
        $this->getSlim()->response()->header('Content-Type', 'image/jpeg');
        echo file_get_contents($sourcePath);
    }

    public function showSmallImage() {
        $args = func_get_arg(0);
        $basePath = $args[0].'/'.$args[1].'/'.$args[2].'/'.$args[3];
        $sourcePath = '../upload/'.$basePath;
        $destPath = 'image/small/'.$basePath;
        if(!file_exists($destPath)) {
            @mkdir(dirname($destPath), 0777, true);
            $sourceImage = imagecreatefromjpeg($sourcePath);
            $sizes = getimagesize($sourcePath);
            if($sizes[1] > 650) {
                $height = 650;
                $width = ($height / $sizes[1]) * $sizes[0];
            }
            else {
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
}
