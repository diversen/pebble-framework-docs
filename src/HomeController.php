<?php

namespace App;

use Pebble\Attributes\Route;

class HomeController {

    #[Route(path: '/user/:username')]
    public function userGreeting(array $params, object $middleware_object) {
        echo "Hello world $params[username]!<br />";
        echo $middleware_object->message . "<br />";

        // Note: You can always get the current route from the router if you need to. 
        echo "Current route is: " . \Pebble\Router::getCurrentRoute();
    }   
}
