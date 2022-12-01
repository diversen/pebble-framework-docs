<?php

include_once "vendor/autoload.php";

use Pebble\Service\MigrationService;

// Get migration instance using service class
$migrate = (new MigrationService())->getMigration();

// This will migrate both SQL files 0001.sql and 0002.sql
// Unless they already have been migrated
$migrate->up(2);

// This would also migrate both version up
// $migrate->up();