<?php

class MainPage {

    /**
     * @var Slim
     */
    private $slim;

    public function __construct($slim) {
        $this->slim = $slim;
    }

    public function index() {
        $this->slim->view()->appendData(
            array('hello' => 'pron, pron, pron!!! '));
        $this->slim->view()->display('main.html');
    }

}
