<?php

require ('../../vendor/autoload.php');

use Pebble\PebbleApp;

$pa = new PebbleApp();

$db = $pa->getDB();

$auth = $pa->getAuth();

$pa->getLog()->debug('Hello world');

$acl = $pa->getACLRole();

$migrate = $pa->getMigration();
var_dump($migrate);
