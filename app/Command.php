<?php

class Command {

    private $slim;
    private $command;

    public function __construct($slim, $command) {
        $this->slim = $slim;
        $this->command = $command;
    }

    public function execute() {
        $classFile = 'app/pages/'.$this->command[0].'.php';
        require_once $classFile;
        $className = $this->command[0];
        $class = new $className($this->slim);
        call_user_func(array($class, $this->command[1]), func_get_args());
    }
}
