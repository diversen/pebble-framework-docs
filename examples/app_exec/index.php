<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppExec;
use Pebble\App\AppBase;
use Pebble\App\StdErrorController;
use Pebble\Router;

$app_exec = new AppExec();

// This is the default error controller. 
// You may set your own error controller, e.g. like this: 
// $app_exec->setErrorController(App\ErrorController::class);

// Create an app to be executed. It does not need to extend AppBase
// But AppBase has some nice utility methods
class MyApp extends AppBase {

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
