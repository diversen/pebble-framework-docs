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
