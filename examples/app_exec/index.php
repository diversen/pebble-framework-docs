<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppExec;
use Pebble\App\StdErrorController;
use Pebble\Router;
use Pebble\App\CommonUtils;

$app_exec = new AppExec();

// This uses is the default error controller. 
// You may set your own error controller, e.g. like this: 
// $app_exec->setErrorController(App\ErrorController::class);
// 
class MyApp {

    
    public function run() {

        // This makes all errors and notices to be thrown as exceptions
        $common_utils = new CommonUtils();   
        $common_utils->setErrorHandler();

        $router = new Router();
        $router->addClass(App\SimpleHomeController::class);
        $router->run();

    }
}

$app_exec->setApp(MyApp::class);
$app_exec->run();
