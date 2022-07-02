<?php

namespace App;

class SimpleHomeController {

    /**
     * @route /
     * @verbs GET,POST
     */
    public function index() {
        echo "Hello world!";
    }

    /**
     * @route /user/:username
     * @verbs GET
     */
    public function userGreeting(array $params) {
        echo "Hello world $params[username]!";
    } 
}
