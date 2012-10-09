<?php

require_once 'libs/MongoAssist.php';
require_once 'libs/Slim/Slim.php';
require_once 'libs/TwigAutoloader.php';

Twig_Extensions_Autoloader::register();

require_once 'libs/TwigView.php';

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
        $slim = $this->getSlim();
        $slim->view()->appendData(array('siteTitle' => self::siteTitle));
        $this->slim->get('/', function () use ($slim) {
            $slim->view()->appendData(
                array('hello' => 'pron, pron, pron!!!'));
            $slim->view()->display('main.html');
        });
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
    }

    public function getSlim() {
        return $this->slim;
    }
}
