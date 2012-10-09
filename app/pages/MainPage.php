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

        $this->getSlim()->view()->display('main.html');
    }

    public function showFullImage() {
        $args = func_get_arg(0);
        $basePath = $args[0].'/'.$args[1].'/'.$args[2].'/'.$args[3];
        $sourcePath = '../upload/'.$basePath;
        header('Content-Type: image/jpeg');
        echo file_get_contents($sourcePath);
    }

}
