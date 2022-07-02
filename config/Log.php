<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Pebble\Path;

// Level
$logger_level = Logger::DEBUG;

// Generate Monolog instance
$logger = new Logger('main');
$base_path = Path::getBasePath();
$logger->pushHandler(new StreamHandler($base_path . '/logs/main.log', $logger_level));
$logger->pushHandler(new StreamHandler('php://stderr', $logger_level));

return [
    'level' => $logger_level,
    'logger' => $logger,
];
