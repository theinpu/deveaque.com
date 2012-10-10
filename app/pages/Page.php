<?php

abstract class Page {

    /**
     * @var Slim
     */
    private $slim;

    public function __construct($slim) {
        $this->slim = $slim;
    }

    protected final function getSlim() {
        return $this->slim;
    }
}
