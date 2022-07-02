<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;
use Pebble\Exception\NotFoundException;
use Pebble\ExceptionTrace;
use Pebble\App\AppBase;

// The app base class is used to provide some basic utilities to the app.
$app_base = new AppBase();

// All errors and notices will be thrown as exceptions
$app_base->setErrorHandler();

try {

    $router = new Router();
    $router->addClass(App\SimpleHomeController::class);
    $router->run();
} catch (NotFoundException $e) {

    // You may show a propper '404 Not Found' page here
    echo $e->getMessage();
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
} catch (Throwable $e) {

    // You may show a '500 Internal Server Error' page here
    // This is an application error
    echo $e->getMessage();
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
}
