<?php
require_once 'Page.php';

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
        //$color = $_POST['color'];
      //   $TagsArr = $this->getSlim()->post('TagsArr');
        $TagsArr = $this->getSlim()->request()->post("TagsArr");

        var_dump($TagsArr);



        //$this->getSlim()->redirect('/');
    }

}