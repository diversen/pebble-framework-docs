<?php

require "../../vendor/autoload.php"; 

use Pebble\Service\DBService;
use Pebble\DBCache;

$db = (new DBService())->getDB();

$cache = new DBCache($db);

// Try to get a result ignoring max age
// $from_cache = $cache->get('some_key');

// Get a result that is max 10 seconds old
$from_cache = $cache->get('some_key', 10);

if (!$from_cache) {
    echo "No cache result<br />";
    echo "Add value to key 'some_key'<br />";
    $to_cache = ['this is a test'];
    // Not set is inside an DB transaction
    $cache->set('some_key', $to_cache);
} else {
    echo "Got value from cache<br />";
    var_dump($from_cache);
}

// Delete a value
// $cache->delete('some_key');