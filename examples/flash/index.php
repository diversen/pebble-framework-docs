<?php

require_once "../../vendor/autoload.php";

use App\FlashTestController;
use Pebble\App\AppBase;
use Pebble\App\AppExec;
use Pebble\Router;

class TestApp  extends AppBase {
    public function run() {
        $this->setErrorHandler();

        // Start session as flash message uses session
        $this->sessionStart();
        
        $router = new Router();
        $router->addClass(FlashTestController::class);
        $router->run();
    }
}

$app_exec = new AppExec();
$app_exec->setApp(TestApp::class);
$app_exec->run();
