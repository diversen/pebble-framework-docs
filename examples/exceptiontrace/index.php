<?php

require "../../vendor/autoload.php";

use Exception;
use Pebble\ExceptionTrace;

try {
    throw new Exception('This went horrible wrong');
} catch (Exception  $e) {
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
}

// This following message will be printed:
//
// Message: This went horrible wrong
// In: /home/dennis/pebble-framework-docs/examples/exceptiontrace/index.php (9)
// Trace: 
// #0 {main}
