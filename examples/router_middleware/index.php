<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;
use Pebble\Exception\NotFoundException;
use Pebble\ExceptionTrace;
use Pebble\Router\Request;

try {

    $router = new Router();
    $router->addClass(App\HomeController::class);

    function middle_ware_1 (Request $request) {
        $request->message = 'From middle_ware_1';
    }

    function middle_ware_2 (Request $request) {
        $request->message = 'From middle_ware_2';
    }

    // Connect the middleware
    $router->use('middle_ware_1');
    $router->use('middle_ware_2');

    // You may set a middleware class which the middleware object will be created from
    // Otherwise it is just a stdClass the object will be created from
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
