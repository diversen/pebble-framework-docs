<?php

require "../../vendor/autoload.php";

use Pebble\Service\ConfigService;
use Pebble\Session;

$config = (new ConfigService())->getConfig();
$session_config = $config->getSection('SessionShort');

Session::setConfigSettings($session_config);
session_start();

if (!isset($_SESSION['started'])) {
    $_SESSION['started'] = random_int(0, 10); 
    echo "Session started has been set";
} else {
    
    echo "Value of \$_SESSION['started'] = $_SESSION[started]";
}


