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

        $this->addGetCommand('/post/delete/:id', 'AdminPage', 'deletePost');
        $this->addPostCommand('/post/edit/:id', 'AdminPage', 'editPost');
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
                                    'debug'          => true,
                                    'log.enable'     => true,
                                    'log.path'       => '../slim-log',
                                    'log.level'      => 4,
                                    'templates.path' => '../templates'
                               ));
        $this->slim->view()->appendData(array('siteTitle' => self::Title));
        $this->showAdminFeatures();
    }

    private function showAdminFeatures() {
        $showAdminFeatures = self::isAdmin();
        $this->getSlim()->view()->appendData(array('showAdminFeatures' => $showAdminFeatures));
    }

    public static function isAdmin() {
        return in_array($_SERVER['REMOTE_ADDR'],
                        array('92.62.59.95',
                              '79.142.82.62',
                              '89.110.48.143',
                              '109.124.94.122'));
    }

    private function getSlim() {
        return $this->slim;
    }
}
