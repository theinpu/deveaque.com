<?php

$incPath = get_include_path();
$rootPath = realpath(dirname(__FILE__).'/../');
set_include_path($incPath.':'.$rootPath);

require_once 'app/Application.php';

$app = new Application();