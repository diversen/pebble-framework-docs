<?php

require_once "../../vendor/autoload.php";

use App\ACLRoleTestController;
use Pebble\App\AppBase;
use Pebble\App\AppExec;
use Pebble\Router;

class TestApp  extends AppBase {
    public function run() {
        $this->setErrorHandler();
        $router = new Router();
        $router->addClass(ACLRoleTestController::class);
        $router->run();
    }
}

$app_exec = new AppExec();
$app_exec->setApp(TestApp::class);
$app_exec->run();
