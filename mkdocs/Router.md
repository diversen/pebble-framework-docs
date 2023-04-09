### Defining routes 

Routes are defined in controller classes, which are then connected to the router.
You will add different routes as class names and then you will run the router: 

* `Router::addClass(App\HomeController::class)`
* `Router::run()`

The first method sets up some application endpoints and the latter method runs the application. An `Exception` is thrown if no valid endpoint is found. 

The router instance is looking for the tags `route` and `verbs` using the attribute `Route`. A simple controller example could look like this:

```src/SimpleHomeController.php ->```

~~~php
<?php

namespace App;

use Pebble\Attributes\Route;
use Pebble\Router\Request;

class SimpleHomeController {

    #[Route(path: '/', verbs: ['GET', 'POST'])]
    public function index() {
        echo "Hello world!";
    }

    #[Route(path: '/user/:username')]
    public function userGreeting(Request $request) {
        $username = $request->param('username');
        echo "Hello world $username!";
    } 
}

~~~

The route will accept the verbs GET and POST and the path `/` will dispatch the method `index`.

The second route using the path `/user/:username` will dispatch the method `userGreeting`. This method transforms the second URL segment into a string parameter, which the controller method may use. This route only accepts GET requests (which is used if no verbs is used).  

The `path` can also be made a bit more complex, like e.g. `/user/:username/actions/:action`. If this route is matched, then the `Request` will contain both `username` and `action` keys and values.


Let's connect the above `SimpleHomeController` class to a router instance in an index.php file: 

```examples/router_simple/index.php ->```

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;

// Init
$router = new Router();

// Add the controller class name to the router
$router->addClass(App\SimpleHomeController::class);

// Run the application
$router->run();
~~~

Run the above example:

    php -S localhost:8000 -t examples/router_simple

If you visit [http://localhost:8000](http://localhost:8000), you should receive a response from the server saying `hello world!`

If you visit [http://localhost:8000/user/helen](http://localhost:8000/user/helen), you should receive a response saying `Hello world helen!`

### Error handling

If you visit a route that is not defined, you may get a 500 error without any useful message, but this depends on your server configuration. 

We will make a setup in order to catch all errors. This will also deliver a better user experience:

```examples/router_error/index.php ->```

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;
use Pebble\Exception\NotFoundException;
use Pebble\ExceptionTrace;

try {
    
    $router = new Router();
    $router->addClass(App\SimpleHomeController::class);
    $router->run();
} catch (NotFoundException $e) {

    // You may show a propper '404 Not Found' page here
    echo $e->getMessage();
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
} catch (Throwable $e) {

    // You may show a '500 Internal Server Error' page here
    // This is an application error
    echo $e->getMessage();
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
}

~~~

You may run this example:

    php -S localhost:8000 -t examples/router_error

If you visit [http://localhost:8000/does/not/exists](http://localhost:8000/does/not/exists), you will get a message saying `The page does not exist`

You will also get a better trace of the error. 

### Middleware

You may add middleware to you application. Middleware are just `callables` which will be called before hitting the controller method. You may specify multiple middleware callables. 

Middleware are called in the order that they are added to your `Router` instance. And the middleware callables will receive the same parameters as your controller.

The second parameter of a callable is an `object`, which is passed from middleware to middleware. And finally it will be sent to the controller method. In the controller method the middleware object is also the second parameter.

Here is a controller where both `$params` and `$middleware_object` are used: 

```src/HomeController.php ->```

~~~php
<?php

namespace App;

use Pebble\Attributes\Route;
use Pebble\Router\Request;

class HomeController {

    #[Route(path: '/user/:username')]
    public function userGreeting(Request $request) {
        $username = $request->param('username');
        $message = $request->message;
        echo "Hello world $username!<br />";
        echo $message . "<br />";

        // Note: You can always get the current route from the request object if you need to. 
        echo "Current route is: " . $request->getCurrentRoute();
    }   
}

~~~

Create an application like this: 

```examples/router_middleware/index.php ->```

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;
use Pebble\Exception\NotFoundException;
use Pebble\ExceptionTrace;
use Pebble\Router\Request;

try {

    $router = new Router();
    $router->addClass(App\HomeController::class);

    # middleware just add a property to the request object
    function middle_ware_1 (Request $request) {
        $request->message = 'From middle_ware_1';
    }

    function middle_ware_2 (Request $request) {
        $request->message = 'From middle_ware_2';
    }

    // Connect the middleware
    $router->use('middle_ware_1');
    $router->use('middle_ware_2');

    $router->run();
} catch (NotFoundException $e) {

    // You may show a propper '404 Not Found' page here
    echo $e->getMessage();
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
} catch (Throwable $e) {

    // You may show a '500 Internal Server Error' page here
    // This is an application error
    echo $e->getMessage();
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
}

~~~

Run the example:

    php -S localhost:8000 -t examples/router_middleware

If you visit [http://localhost:8000/user/helen](http://localhost:8000/user/helen), you should get the following response: 

    Hello world helen!
    From middle_ware_2
    Current route is: /user/:username

<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/100-Router.md'>Edit this page on GitHub</a>