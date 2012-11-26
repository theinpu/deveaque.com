<?php

require_once 'libs/MongoAssist.php';
require_once 'app/models/PostFactory.php';
require_once 'app/models/Tags.php';

class ImportQueue implements CronModule {

    /**
     * @var MongoCollection
     */
    private $queueCollection;

    private $uploadPath = '';

    public function exec($params = array()) {
        $this->uploadPath = dirname(__FILE__).'/../../uploads/';
        $this->queueCollection = MongoAssist::GetCollection('import_queue');
        $this->parseItem('/ftp/[Women Name][Photographer Name][tag1,tag2,tag3].jpg');
        $this->parseQueue();
    }

    private function parseQueue() {
        $cursor = $this->queueCollection->find()->sort(array('time' => -1))->limit(10);
        while($cursor->hasNext()) {
            $item = $cursor->getNext();
            $this->parseItem($item['path']);
            $this->queueCollection->remove(array('_id' => $item['_id']));
        }
    }

    private function parseItem($path) {
        $baseName = explode('.', basename($path));
        preg_match_all('/\[([\w|\s|,|-]*)\]/is', $baseName[0], $matches, PREG_SET_ORDER);
        $title = $matches[0][1];
        $photographer = $matches[1][1];
        $tags = explode(',', $matches[2][1]);
        $file = $this->importImage($path);

        $post = new Post(array(
                              'title'        => empty($title) ? null : $title,
                              'photographer' => empty($photographer) ? null : $photographer,
                              'file'         => $file
                         ));
        PostFactory::createPost($post);
        foreach($tags as $tag) {
            Tags::attachPost($tag, $post->getId());
        }
    }

    private function importImage($file) {
        $datePath = date('Y').'/'.date('m').'/'.date('d').'/';
        $destPath = $this->uploadPath.$datePath;

        $baseName = explode('.', basename($file));
        $fileName = md5($baseName[0]).'.'.$baseName[1];

        copy($file, $destPath.$fileName);
        unlink($file);

        return $datePath.$fileName;
    }
}
