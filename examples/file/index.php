<?php

include_once "../../vendor/autoload.php";

use Pebble\File;
use Pebble\Path;

// All config/ files
$files = File::dirToArray(Path::getBasePath() . '/config');
var_dump($files);