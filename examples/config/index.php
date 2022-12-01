<?php

require_once "../../vendor/autoload.php";

use Pebble\Service\ConfigService;

// Get the service from the ConfigService class
$config = (new ConfigService())->getConfig();

// Is dev. Because config-locale/ overrides config/
if ($config->get('App.env')) {
    echo  "Env is: " . $config->get('App.env') . "<br />";
    // -> Env is: dev
} else {
    echo "Env is not defined!<br />";
}

// No override in config-locale/ so we just get 'A secret!'
echo "What is the secret. It is: " . $config->get('App.secret') . "<br />";
// -> What is the secret. It is: A secret!

// You can get a complete configuration section like this:
echo "var_dump all settings in the section App (App.php):<br />";
var_dump($config->getSection('App'));
// -> array(2) { ["env"]=> string(3) "dev" ["secret"]=> string(9) "A secret!" }

