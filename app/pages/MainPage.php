<?php

class MainPage extends Page {

    public function index() {
        $cursor = MongoAssist::GetCollection('posts')->find()
            ->sort(array('date' => -1))
            ->skip(0)->limit(10);

        $posts = array();

        while($cursor->hasNext()) {
            $item = $cursor->getNext();
            $imageTmbPath = '/image/small/'.$item['file'];
            $imagePath = '/image/full/'.$item['file'];
            $post = array(
                'tmb'   => $imageTmbPath,
                'image' => $imagePath,
                'date'  => date('Y-m-d H:i:s', $item['date'])
            );
            $posts[] = $post;
        }

        $this->getSlim()->view()->appendData(array('posts' => $posts));

        $this->getSlim()->view()->display('main.twig');
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
