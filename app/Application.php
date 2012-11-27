<?php
use \Slim\Slim;

require_once 'libs/MongoAssist.php';
require_once 'libs/Slim/Slim.php';
Slim::registerAutoloader();
require_once 'libs/TwigAutoloader.php';

Twig_Extensions_Autoloader::register();

require_once 'libs/TwigView.php';

require_once 'app/Command.php';
require_once 'app/models/Users.php';


class Application {

    const Title = 'Deveaque.com - inspiration girls';

    /**
     * @var Slim
     */
    private $slim;

    public function __construct() {
        $this->initializeSlim();
        $this->createRoutes();
        if(isset($_SERVER['DEVELOP'])) {
            $this->slim->lastModified(time() - 2);
            $this->slim->expires(time() - 1);
        }
        $this->slim->run();
    }

    private function createRoutes() {
        $this->createBaseSiteCommands();

        $this->addGetCommand('/register', 'RegisterHandler', 'showRegister');
        $this->addGetCommand('/user', 'RegisterHandler', 'showUserSettings');
        $this->addGetCommand('/logout', 'RegisterHandler', 'logout');
        $this->addPostCommand('/login', 'RegisterHandler', 'login');
        $this->addPostCommand('/register', 'RegisterHandler', 'register');

        $this->createContentCommands();
        $this->createAdminCommands();
    }

    private function createBaseSiteCommands() {
        $this->addGetCommand('/(page:pageId)', 'MainSitePages', 'showDefault');
        $this->addGetCommand('/post/:title/(page:pageId)', 'MainSitePages', 'showByTitle');
        $this->addGetCommand('/post/:id', 'MainSitePages', 'showPost');
        $this->addGetCommand('/tag/:tag/(page:pageId)', 'MainSitePages', 'showByTag');
        $this->addGetCommand('/tag/search', 'MainSitePages', 'searchTag');
    }

    private function createContentCommands() {
        $this->addGetCommand('/image/full/:year/:month/:day/:image', 'ContentHandler', 'showFullPostImage');
        $this->addGetCommand('/image/small/:year/:month/:day/:image', 'ContentHandler', 'showSmallPostImage');
    }

    private function createAdminCommands() {
        $this->addGetAdminCommand('/upload', 'AdminPage', 'showUpload');
        $this->addPostAdminCommand('/post/add', 'PostHandler', 'addPost');
        $this->addPostAdminCommand('/post/edit/:id', 'PostHandler', 'editPost');
        $this->addGetAdminCommand('/post/delete/:id', 'PostHandler', 'deletePost');
        $this->addPostAdminCommand('/tag/save', 'TagHandler', 'saveTag');
        $this->addGetAdminCommand('/tag/:tag/attach/:post', 'TagHandler', 'attachTagToPost');
        $this->addGetAdminCommand('/tag/:tag/deattach/:post', 'TagHandler', 'deattachTag');
        $this->addGetAdminCommand('/editors/post/:id', 'EditorsHandler', 'getPostEditor');
        $this->addGetAdminCommand('/editors/tag/:id', 'EditorsHandler', 'getTagEditor');
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
                                    'debug'          => $_SERVER['DEVELOP'],
                                    'log.enable'     => true,
                                    'log.path'       => '../slim-log',
                                    'log.level'      => 4,
                                    'templates.path' => '../templates'
                               ));
        $this->setErrorPage();
        $this->setNotFoundPage();
        $this->setupGlobalTemplateData();
    }

    private function setNotFoundPage() {
        $slim = $this->getSlim();
        $this->getSlim()->notFound(function () use ($slim) {
            $slim->view()->appendData(array('errorNumber' => 404, 'errorMessage' => 'Страница не найдена'));
            $slim->view()->display('error.twig');
        });
    }

    private function setErrorPage() {
        $slim = $this->getSlim();
        $this->getSlim()->error(function (Exception $e) use ($slim) {
            $slim->view()->appendData(array(
                                           'errorNumber'  => $e->getCode(),
                                           'errorMessage' => $e->getMessage(),
                                           'errorTrace'   => print_r($e->getTrace(), true)
                                      ));
            $slim->view()->display('error.twig');
        });
    }

    private function setupGlobalTemplateData() {
        $this->getSlim()->view()->appendData(array('siteTitle' => self::Title));
        $this->getSlim()->view()->appendData(array('logoTitle' => self::Title));
        $this->getSlim()->view()->appendData(array('isAdmin' => self::isAdmin()));
        $this->getSlim()->view()->appendData(array('isDevelop' => $_SERVER['DEVELOP']));

        $user = Users::getCurrentUser();
        $this->getSlim()->view()->appendData(array('user' => $user->isGuest() ? null : $user));
    }

    public static function isAdmin() {
        return $_SERVER['DEVELOP'] || in_array($_SERVER['REMOTE_ADDR'],
                                               array('92.62.59.95',
                                                     '89.110.48.143'));
    }

    private function getSlim() {
        return $this->slim;
    }

}
