<?php

include_once "../../vendor/autoload.php";

use Pebble\Service\ConfigService;
use Pebble\Service\DBService;
use Pebble\DB;

// Get a config instance
$config = (new ConfigService())->getConfig();

// Get DB section from config
$db_config = $config->getSection('DB');
// ->
// [   
//     'url' => 'mysql:host=127.0.0.1;dbname=pebble', 
//     'username' => 'root', 
//     'password' => 'password', ];
// ]

// Connect
$db = new DB($db_config['url'], $db_config['username'], $db_config['password']);

// Or just get instance using the DBService
$db = (new DBService())->getDB(); 
 