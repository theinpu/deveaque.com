<?php
require_once 'Page.php';

class SandboxHandler extends Page{


    public function showNewPics(){

        $TagsArr=array(
            'color' =>  ""
        );
        $this->getSlim()->render('sandbox/new.twig', array('path' => self::ShowLastPic(), 'TagsArr' => $TagsArr));
    }

    static public function ShowLastPic()
    {
        $FilesArr = scandir('/home/deveaque/Content');
        $fullPath = "/image/Content/".$FilesArr[3];
        return $fullPath;
    }

     public function PostPic()
    {
        //$color = $_POST['color'];
        $TagsArr[] = $_POST['TagsArr[]'];


        var_dump($TagsArr);


        //$this->getSlim()->redirect('/');
    }

}