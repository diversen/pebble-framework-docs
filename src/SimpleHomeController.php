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
