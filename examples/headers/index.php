<?php

require "../../vendor/autoload.php";

use Pebble\Headers;

// Redirect current url to https
Headers::redirectToHttps();