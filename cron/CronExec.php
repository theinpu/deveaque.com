<?php

require_once 'CronModule.php';

class CronExec {

    private $moduleName;
    private $params = array();

    public function __construct($options = array()) {
        $this->checkArguments();
        $this->moduleName = $_SERVER['argv'][1];
        $this->fillParams();
    }

    private function fillParams() {
        if($_SERVER['argc'] > 2) {
            $this->params = array_slice($_SERVER['argv'], 2);
        }
    }

    private function checkArguments() {
        if($_SERVER['argc'] < 2) {
            throw new InvalidArgumentException();
        }
    }

    public function run() {
        $this->loadModule();
        $module = new $this->moduleName;
        $module->exec($this->params);
    }

    private function loadModule() {
        $file = getcwd().'/modules/'.$this->moduleName.'.php';
        if(!file_exists($file)) {
            throw new RuntimeException($file);
        }
        require_once $file;
    }

}
