<?php

require_once 'libs/MongoAssist.php';
require_once 'libs/Slim/Slim.php';
require_once 'libs/TwigAutoloader.php';

Twig_Extensions_Autoloader::register();

require_once 'libs/TwigView.php';

require_once 'app/Command.php';

class Application {

    const Title = 'deveaque.com - прекрасное рядом';

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
        $this->addGetCommand('/image/small/:year/:month/:day/:image', 'MainPage', 'showSmallImage');
        $this->addGetCommand('/image/full/:year/:month/:day/:image', 'MainPage', 'showFullImage');

        $this->addGetCommand('/upload', 'UploadPage', 'index');
        $this->addPostCommand('/upload', 'UploadPage', 'uploadImages');
    }

    private function addGetCommand($path, $class, $method) {
        $command = new Command($this->getSlim(), array($class, $method));
        $this->slim->get($path, $command->getCallback());
    }

    private function addPostCommand($path, $class, $method) {
        $command = new Command($this->getSlim(), array($class, $method));
        $this->slim->post($path, $command->getCallback());
    }

    private function initializeSlim() {
        $this->slim = new Slim(array(
                                    'view'           => new TwigView(),
                                    'debug'          => false,
                                    'log.enable'     => true,
                                    'log.path'       => '../logs',
                                    'log.level'      => 4,
                                    'templates.path' => '../templates'
                               ));
    }

    private function getSlim() {
        return $this->slim;
    }
}
