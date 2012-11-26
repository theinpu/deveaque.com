<?php
$_SERVER['DEVELOP'] = strpos(getcwd(), 'develop') !== false;

$incPath = get_include_path();
$rootPath = realpath(dirname(__FILE__).'/../');
set_include_path($rootPath.':'.$incPath);

require_once 'CronExec.php';

try {
    $cron = new CronExec();
    $cron->run();
}
catch(InvalidArgumentException $iae) {
    echo "Usage: php cron.php ModuleName [options]\r\n";
}
catch(RuntimeException $re) {
    echo "Module file not found (".$re->getMessage().")\r\n";
}
