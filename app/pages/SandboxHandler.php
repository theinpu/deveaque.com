<?php
require_once 'Page.php';

class SandboxHandler extends Page{


    public function showNewPics(){


        $this->getSlim()->render('sandbox/new.twig', array('path' => self::ShowLastPic()));
    }

    static public function ShowLastPic()
    {
        $FilesArr = scandir('/home/deveaque/Content');
        $fullPath = "/image/Content/".$FilesArr[3];
        return $fullPath;
    }


}