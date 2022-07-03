## Flash

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

(src/FlashTestController.php) -&gt;

~~~php
<?php

namespace App;

use Pebble\Flash;
use App\AppBase;

$app_base = new AppBase();
$app_base->setErrorHandler();
$app_base->setIncludePath();

class FlashTestController
{
    private $flash;
    function __construct()
    {
        $this->flash = new Flash();
    }

    /**
     * @route /
     * @verbs GET
     */
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

    /**
     * @route /click
     * @verbs GET
     */
    public function click(array $params, Object $object)
    {
        $random = rand(0, 10);
        $message = "Your clicked a link and got this random number: $random";
        $this->flash->setMessage($message, 'info', ['flash_remove' => true]);
        header("Location: /");
    }
}

~~~

We execute this controller in our `index.php` file: 

(examples/flash/index.php) -&gt;

~~~php
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

~~~

Run the example: 

    php -S localhost:8000 -t examples/flash

You may then visit [http://localhost:8000](http://localhost:8000)

