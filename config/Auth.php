<?php

return
[
    'cookie_path' => '/',
    'cookie_secure' => true,
    'cookie_domain' => $_SERVER['SERVER_NAME'] ?? '',
    'cookie_http' => true
];