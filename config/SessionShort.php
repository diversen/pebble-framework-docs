<?php

return [
    'lifetime' => 10, // Seconds
    'path' => '/',
    // prefix with a dot to use all domains e.g. .php.net
    'domain' => $_SERVER['SERVER_NAME'] ?? '',
    'secure' => true, //
    'httponly' => true,
];
