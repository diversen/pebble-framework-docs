<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppExec;
use Pebble\App\StdErrorController;
use Pebble\Router;

$app_exec = new AppExec();

// This is the default error controller. 
// You may set your own error controller, e.g. like this: 
// $app_exec->setErrorController(App\ErrorController::class);
// 
// Create an app to be executed. It uses the MainUtils trait
// in order to call setErrorHandler() method.
// 
// This causes all errors and notices to be thrown as exceptions.
class MyApp {

    use \Pebble\Trait\MainUtils;
    
    public function run() {

        // Throw on all errors and notices
        $this->setErrorHandler();

        $router = new Router();
        $router->addClass(App\SimpleHomeController::class);
        $router->run();

    }
}

$app_exec->setApp(MyApp::class);
$app_exec->run();
