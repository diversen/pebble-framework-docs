<?php

require_once "../../vendor/autoload.php";

use Pebble\JSON;

// Add debug info to the JSON array
JSON::$debug = false; // Default is false

// Send JSON header and response
echo JSON::response(['some value', 'some other value']);

// Outputs -> 
// {"0":"some value","1":"some other value","__POST":[],"__GET":[]}

// Outputs -> (IF JSON::$debug is false)
// ["some value","some other value"]