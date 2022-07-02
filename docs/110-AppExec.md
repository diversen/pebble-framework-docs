## AppExec

The above router class showed that it is quite easy to make an
execution environment, where any errors thrown will be caught. 

There is such an app execution environment built-in and it is easy to
use. Let's try and use the class `Pebble\AppExec` in the following example:

<a href='https://github.com/diversen/pebble-framework-docs/blob/main/examples/app_exec/index.php' target='_blank'>examples/app_exec/index.php</a>

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppExec;
use Pebble\App\AppBase;
use Pebble\App\StdErrorController;
use Pebble\Router;

$app_exec = new AppExec();

// This is the default error controller. You may set your own error controller
// $app_exec->setErrorController(StdErrorController::class);

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

~~~

You may add your own error controller.

The only public method in `Pebble\StdErrorController` is `render`. 
Your own error controller will need to have a `render` method. This method
has a single `param` which is an `Exception`.   

The directories `config` and `config-locale` MUST exist a the same level
as the composer `vendor` dir. The `logs` dir will be created automatically
when the first log message is written. This is also created at the same level
as the `vendor` dir. So this is the directory structure: 

    config/
    config-locale/
    vendor/
    logs/

You will need the [Monolog](https://github.com/Seldaek/monolog) package. 
This is used for writing the log messages:

    composer require monolog/monolog

Run the example:

    php -S localhost:8000 -t examples/app_exec/

You may now visit e.g. [http://localhost:8000/](http://localhost:8000/)

And you will get a `hello world!`. 

You can also visit a route that does not exist: 
[http://localhost:8000/does/not/exists](http://localhost:8000/does/not/exists)

And you will get an error message and a stack trace. 

If you open the log file: 

    more logs/main.log

You will notice that a log message has been appended. 
