<?php
require_once 'Page.php';

class SandboxHandler extends Page{


    public function showNewPics(){

        $this->getSlim()->view()->display('sandbox/new.twig');

    }

}