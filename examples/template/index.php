<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;
use Pebble\App\AppBase;
use Pebble\App\AppExec;

class MyApp extends AppBase {

    public function run () {

        $this->setErrorHandler();
        
        // Add src to include path 
        // Then templates are loaded without adding 'src' to the path
        $this->addSrcToIncludePath();

        $router = new Router();
        $router->addClass(App\TemplateTest::class);
        $router->run();

    }
}

$app_exec = new AppExec();
$app_exec->setApp(MyApp::class);
$app_exec->run();
