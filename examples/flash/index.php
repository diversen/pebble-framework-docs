<?php

require_once "../../vendor/autoload.php";

use App\FlashTestController;
use Pebble\App\AppExec;
use Pebble\Router;
use Pebble\App\CommonUtils;

class TestApp {


    public function run() {

        $common_utils = new CommonUtils();
        $common_utils->setErrorHandler();

        // Start session as flash message uses session
        $common_utils->sessionStart();
        
        $router = new Router();
        $router->addClass(FlashTestController::class);
        $router->run();
    }
}

$app_exec = new AppExec();
$app_exec->setApp(TestApp::class);
$app_exec->run();
