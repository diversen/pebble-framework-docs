<?php

require_once "../../vendor/autoload.php";

use App\ACLTestController;
use Pebble\App\AppExec;
use Pebble\Router;
use Pebble\App\CommonUtils;

class TestApp {
    
    public function run() {

        $common_utils = new CommonUtils();   
        $common_utils->setErrorHandler();

        $router = new Router();
        $router->addClass(ACLTestController::class);
        $router->run();
    }
}

$app_exec = new AppExec();
$app_exec->setApp(TestApp::class);
$app_exec->run();
