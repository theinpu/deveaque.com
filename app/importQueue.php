<?php

if($argc < 2) {
    echo "Usage: php importQueue.php source/file \r\n";
    exit;
}

$incPath = get_include_path();
$rootPath = realpath(dirname(__FILE__).'/../');
set_include_path($rootPath.':'.$incPath);

$_SERVER['DEVELOP'] = strpos($rootPath, 'develop') !== false;

require_once 'libs/MongoAssist.php';

$queue = MongoAssist::GetCollection('import_queue');

$source = $argv[1];
if(!file_exists($source)) {
    echo "File: ".$source." not found\r\n";
    exit;
}

$dest = '/var/www/deveaque/data/queue/'.basename($source);

copy($source, $dest);

$data = array(
    'path' => $dest,
    'time' => time()
);

$queue->insert($data);