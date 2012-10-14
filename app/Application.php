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
        $this->addGetCommand('/post/:title/(page:pageId)', 'MainPage', 'showByTitle');
        $this->addGetCommand('/tag/:tag', 'MainPage', 'showByTag');
        $this->addGetCommand('/tag/cloud', 'TagsPage', 'showTagCloud');

        $this->addGetAdminCommand('/upload', 'UploadPage', 'index');
        $this->addPostAdminCommand('/upload', 'UploadPage', 'uploadImages');

        $this->addGetAdminCommand('/post/delete/:id', 'AdminPage', 'deletePost');
        $this->addPostAdminCommand('/post/edit/:id', 'AdminPage', 'editPost');
        $this->addGetAdminCommand('/post/edit/form/:id', 'AdminPage', 'getEditor');


        $this->addGetAdminCommand('/tag/editor/:id', 'AdminPage', 'getTagEditor');
        $this->addPostAdminCommand('/tag/save', 'TagsPage', 'saveTag');
        $this->addGetAdminCommand('/tag/:tag/attach/:post', 'TagsPage', 'attachTagToPost');
        $this->addGetAdminCommand('/tag/:tag/deattach/:post', 'TagsPage', 'deattachTag');

        /*
         * base view
         * /(page) - default view
         * /post/title/(page) - show by title
         * /tag/title/(page) - show by tag
         *
         * content
         * /image/full/year/month/day/image
         * /image/small/year/month/day/image
         * /style/cssFile
         * /script/jsFile
         *
         * admin
         * /upload
         *
         * admin actions
         * /post/add
         * /post/edit/id
         * /post/delete/id
         * /tag/save
         * /tag/tagTitle/attach/postId
         * /tag/tagTitle/deattach/postId
         *
         * editors
         * /post/edit/form/id   => /editors/post/id
         * /tag/editor/id       => /editors/tag/id
         *
         */

    }

    private function addGetCommand($path, $class, $method) {
        $command = new Command($this->getSlim(), array($class, $method));
        $this->slim->get($path, $command->getCallback());
    }

    private function addPostCommand($path, $class, $method) {
        $command = new Command($this->getSlim(), array($class, $method));
        $this->slim->post($path, $command->getCallback());
    }

    private function addGetAdminCommand($path, $class, $method) {
        if(!self::isAdmin()) return;
        $command = new Command($this->getSlim(), array($class, $method));
        $this->slim->get($path, $command->getCallback());
    }

    private function addPostAdminCommand($path, $class, $method) {
        if(!self::isAdmin()) return;
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
        $this->slim->view()->appendData(array('logoTitle' => self::Title));
        $this->showAdminFeatures();
    }

    private function showAdminFeatures() {
        $showAdminFeatures = self::isAdmin();
        $this->getSlim()->view()->appendData(array('isAdmin' => $showAdminFeatures));
    }

    public static function isAdmin() {
        return in_array($_SERVER['REMOTE_ADDR'],
                        array('92.62.59.95',
                              '89.110.48.143'));
    }

    private function getSlim() {
        return $this->slim;
    }

}
