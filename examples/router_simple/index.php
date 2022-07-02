<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;

// Init
$router = new Router();

// Add the controller class name to the router
$router->addClass(App\SimpleHomeController::class);

// Run the application
$router->run();