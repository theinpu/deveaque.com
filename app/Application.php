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

        $mainPageCommand = new Command($this->getSlim(), array('MainPage', 'index'));
        $this->slim->get('/(page:pageId)', array($mainPageCommand, 'execute'));

        /*$this->slim->get('/(page:pageId)', function ($pageId = 1) use ($slim) {
            $slim->view()->appendData(
                array('hello' => 'pron, pron, pron!!! '.'page - '.$pageId));
            $slim->view()->display('main.html');
        });

        $this->slim->get('/image/:imageId', function($imageId) use ($slim) {

        });*/
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
