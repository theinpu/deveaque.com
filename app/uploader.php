<?php

$incPath = get_include_path();
$rootPath = realpath(dirname(__FILE__).'/../');
set_include_path($rootPath.':'.$incPath);

error_reporting(E_ALL & ~E_NOTICE);

require_once 'libs/MongoAssist.php';
require_once 'models/PostFactory.php';
require_once 'models/Tags.php';

$args = array();
for($i = 1; $i < $argc; $i++) {
    $arg = explode('=', $argv[$i]);
    $args[$arg[0]] = $arg[1];
}

if(isset($args['file'])) {
    $file = file_get_contents(realpath($args['file']));
    $data = json_decode($file);
    if(!isset($data['file'])) {
        die("need image path");
    }
    if(!isset($data['date'])) {
        $data['date'] = date('U');
    }
    $post = new Post($data);
    PostFactory::createPost($post);
}