<?php

require_once 'app/pages/Page.php';

class Command {

    private $slim;
    private $command;

    public function __construct($slim, $command) {
        $this->slim = $slim;
        $this->command = $command;
    }

    public function execute() {
        $classFile = $_SERVER['DOCUMENT_ROOT'].'/../app/pages/'.$this->command[0].'.php';
        if(!file_exists($classFile)) {
            throw new InvalidArgumentException('Page not found ('.$classFile.')');
        }
        require_once $classFile;
        $className = $this->command[0];
        $class = new $className($this->slim);
        call_user_func(array($class, $this->command[1]), func_get_args());
    }

    public function getCallback() {
        return array($this, 'execute');
    }
}
