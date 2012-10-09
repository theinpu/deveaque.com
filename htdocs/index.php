<?php

$incPath = get_include_path();
$rootPath = realpath(dirname(__FILE__).'/../');
set_include_path($incPath.':'.$rootPath);

require_once 'libs/Slim/Slim.php';
require_once 'libs/TwigAutoloader.php';

Twig_Extensions_Autoloader::register();

require_once 'libs/TwigView.php';

$slim = new Slim(array(
                      'view'           => new TwigView(),
                      'debug'          => true,
                      'log.enable'     => false,
                      'log.path'       => '../logs',
                      'log.level'      => 4,
                      'templates.path' => '../data/templates'
                 ));

$slim->get('/', function () use ($slim) {
    echo 'PRON!!!';
});

$slim->run();