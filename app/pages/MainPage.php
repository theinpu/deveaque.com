<?php

class MainPage extends Page {

    public function index() {
        $this->getSlim()->view()->display('main.html');
    }

}
