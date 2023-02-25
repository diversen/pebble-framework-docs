<?php

namespace App;

use Pebble\Attributes\Route;

class SimpleHomeController {

    #[Route(path: '/', verbs: ['GET,POST'])]
    public function index() {
        echo "Hello world!";
    }

    #[Route(path: '/user/:username')]
    public function userGreeting(array $params) {
        echo "Hello world $params[username]!";
    } 
}
