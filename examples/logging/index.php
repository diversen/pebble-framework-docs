<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppBase;
use Pebble\Service\LogService;

// You can get a log instance from AppBase
$log = (new AppBase())->getLog();

// Just a message
$log->debug('Debug message');

// Add some more info as an array
$log->debug('Debug message', ['info' => 'Debug info', 'URL' => $_SERVER['REQUEST_URI']]);

// Or you can get a log instance from LogService
// (It is the same instance you will get)
$log = (new LogService())->getLog();

// Just a message
$log->error('Error message');

// Add some more info as an array
$log->error('Error message', ['info' => 'Error info', 'URL' => $_SERVER['REQUEST_URI']]);