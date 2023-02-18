<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;
use Pebble\App\AppExec;
use Pebble\App\CommonUtils;

class MyApp {

    public function run () {
        $common_utils = new CommonUtils();
        $common_utils->setErrorHandler();
        
        // Add src to include path 
        // Then templates are loaded without adding 'src' to the path
        $common_utils->addSrcToIncludePath();

        $router = new Router();
        $router->addClass(App\TemplateTest::class);
        $router->run();

    }
}

$app_exec = new AppExec();
$app_exec->setApp(MyApp::class);
$app_exec->run();
