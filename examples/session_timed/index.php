<?php

require "../../vendor/autoload.php";

use Pebble\SessionTimed;

session_start();

$session_timed = new SessionTimed();
if (!$session_timed->getValue('test')) {
    echo "Setting new random int as session variable<br>";
    echo "Regardless of the general session's lifetime<br />";
    echo "The random value will exist for 5 seconds";
    $session_timed->setValue('test', random_int(0, 1000000), 5);
} else {
    echo "This is the random int: " . $session_timed->getValue('test');
}





