<?php
require_once 'Page.php';
require_once __DIR__.'/../../libs/MongoAssist.php';
require_once __DIR__.'/../models/PostFactory.php';
require_once __DIR__.'/../models/Tags.php';

class SandboxHandler extends Page{


    public function showNewPics(){

        $TagsArr = array();
        //$this->getSlim()->render('sandbox/new.twig', array('path' => self::ShowLastPic(), 'TagsArr' => $TagsArr));
        $this->appendDataToTemplate(array('path' => self::ShowLastPic()));
        $this->appendDataToTemplate(array('post' => $TagsArr));
        $this->displayTemplate('sandbox/new.twig');


    }

    static public function ShowLastPic()
    {
        $FilesArr = scandir('/home/deveaque/Content');
        $fullPath = "/image/Content/".$FilesArr[3];
        return $fullPath;
    }

    public function PostPic()
    {
        $CurrentDate = date('Y\/m\/d');
        if(!file_exists(__DIR__.'/../../upload/'.$CurrentDate))
        {
           mkdir(__DIR__.'/../../upload/'.$CurrentDate,0755,true );
        }
        $md5name = '/'.md5_file('.'.self::ShowLastPic());
        $TagsArr = $this->getSlim()->request()->post("TagsArr");

        rename('.'.self::ShowLastPic(),__DIR__.'/../../upload/'.$CurrentDate.$md5name.'.jpg') ;

        $data['file']           = $CurrentDate.$md5name.'.jpg';
        $data['photographer']   = $TagsArr['PhotographerName'];
        $data['title']          = $TagsArr['WomenName'];
        $data['date']           = date('U');
        $tags                   = $TagsArr;

        if(!isset($data['file'])) {
            die("need image path");
        }

        $post = new Post($data);
        PostFactory::createPost($post);
        if(isset($tags)) {
            foreach($tags as $tag) {
               echo "<br>attached tag: ".$tag;
                  if($tag != '')
                  {
                      Tags::saveTag($tag);
                      Tags::attachPost($tag, $post->getId());
                  }
            }
        }
        $this->getSlim()->redirect('/sandbox/new');
    }

}