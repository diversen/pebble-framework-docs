<?php

require_once "../../vendor/autoload.php";

use App\ACLRoleTestController;
use Pebble\App\AppExec;
use Pebble\Router;

class TestApp {

    use \Pebble\Trait\MainUtils;
    
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
