<?php

include_once "vendor/autoload.php";

use Pebble\Service\MigrationService;

// Get migration instance using service class
$migrate = (new MigrationService())->getMigration();

// This will migrate both SQL files 0001.sql and 0002.sql
// Unless they already have been migrated
$migrate->down();

// You could also use
// $migrate->down(0);

// Migrate down to version 1. Drops tables in 0002.sql
// $migrate->down(1);

// Migrate down to version 0 Drops tables in 0001.sql
// $migrate->down(0);