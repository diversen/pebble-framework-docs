<?php

require "../../vendor/autoload.php";

use Pebble\Server;

$scheme_and_host = (new Server())->getSchemeAndHost();
echo $scheme_and_host;

// Prints something like this
// -> http://localhost:8000

