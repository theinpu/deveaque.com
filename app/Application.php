<?php

require_once 'libs/MongoAssist.php';
require_once 'libs/Slim/Slim.php';
require_once 'libs/TwigAutoloader.php';

Twig_Extensions_Autoloader::register();

require_once 'libs/TwigView.php';

require_once 'app/Command.php';

class Application {

    const siteTitle = 'deveaque.com - картинки с пылкими телочками';

    /**
     * @var Slim
     */
    private $slim;

    public function __construct() {
        $this->initializeSlim();
        $this->createRoutes();
        $this->slim->run();
    }

    private function createRoutes() {
        $this->addGetCommand('/(page:pageId)', 'MainPage', 'index');
        $this->addGetCommand('/upload', 'UploadPage', 'index');
        $this->addPostCommand('/upload', 'UploadPage', 'uploadImage');
    }

    private function addGetCommand($path, $class, $method) {
        $command = new Command($this->getSlim(), array($class, $method));
        $this->slim->get($path, $command->getCallback());
    }

    private function addPostCommand($path, $class, $method) {
        $command = new Command($this->getSlim(), array($class, $method));
        $this->slim->get($path, $command->getCallback());
    }

    private function initializeSlim() {
        $this->slim = new Slim(array(
                                    'view'           => new TwigView(),
                                    'debug'          => true,
                                    'log.enable'     => false,
                                    'log.path'       => '../logs',
                                    'log.level'      => 4,
                                    'templates.path' => '../templates'
                               ));
        $this->slim->view()->appendData(array('siteTitle' => self::siteTitle));
    }

    public function getSlim() {
        return $this->slim;
    }
}
