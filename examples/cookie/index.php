<?php

require_once "../../vendor/autoload.php";

use Pebble\Cookie;
use Pebble\Service\ConfigService;

// Read the config to use with the cookie
$config = (new ConfigService())->getConfig();
$cookie_settings = $config->getSection('Auth');

// Create cookie object
$cookie = new Cookie($cookie_settings);

// Cookie will last for 10 seconds
if (isset($_COOKIE['test'])) {
    echo "Value of the cookie 'test': " .  $_COOKIE['test'];
} else {
    $cookie->setCookie('test', rand(), 10);
    echo "Random 'test' cookie value has been set. Will exist for 10 seconds";
}
