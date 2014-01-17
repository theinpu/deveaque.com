<?php
require_once 'Page.php';

class SandboxPosting extends Page{

   public function PostPic()
   {
       $color = $_POST['color'];



       $this->getSlim()->redirect('/');
   }

}