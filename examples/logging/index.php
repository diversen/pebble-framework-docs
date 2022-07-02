<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppBase;
use Pebble\Service\LogService;

// You can get a log instance from AppBase
$log = (new AppBase())->getLog();
$log->debug('Some debug message');

// Or you can get a log instance from LogService
// (It is the same instance you will get)
$log = (new LogService())->getLog();
$log->error('Some error message');