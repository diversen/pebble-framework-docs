Flash messages are messages that are displayed to the client -
usually after some action has been performed. 

If the client has logged in, then it is normal to display 
a message like *Welcome. You are now logged in*.

In the following example we make two routes, `/` and `/click`. 

The `/` route just shows a link, that says *Click me*. When 
clicking this link we navigate to `/click` which sets a flash message
in the `$_SESSION['flash']` variable. 

Then we are redirected back to `/`, where the flash messages are 
displayed and cleared.  

```src/FlashTestController.php ->```

~~~php
<?php

namespace App;

use Pebble\Flash;
use Pebble\Attributes\Route;

class FlashTestController
{
    private $flash;
    function __construct()
    {
        $this->flash = new Flash();
    }

    #[Route(path: '/')]
    public function index()
    {
        $flash_str = '';
        $flashes = $this->flash->getMessages();
        foreach ($flashes as $flash) {
            $flash_str .= $flash['message'] . " ($flash[type]) ";
        }

        $content = '<div><a href="/click">Click me</a></div>';
        if ($flash_str) {
            $content .= "<div style='background-color: lightgreen'>$flash_str</div>";
        }
        
        echo $content;
    }

    #[Route(path: '/click')]
    public function click()
    {
        $random = rand(0, 10);
        $message = "Your clicked a link and got this random number: $random";
        $this->flash->setMessage($message, 'info', ['flash_remove' => true]);
        header("Location: /");
    }
}

~~~

We execute this controller in our `index.php` file: 

```examples/flash/index.php ->```

~~~php
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

~~~

Run the example: 

    php -S localhost:8000 -t examples/flash

You may then visit [http://localhost:8000](http://localhost:8000)



<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/910-Flash.md'>Edit this page on GitHub</a>