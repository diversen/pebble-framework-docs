<?php

require_once "vendor/autoload.php";

use Pebble\Special;

// Encode a string
echo Special::encodeStr('<p>This is a test</p>') . "\n";
// -> &lt;p&gt;This is a test&lt;/p&gt;

// Encode an array. It will work recursively if the array contains other arrays
$ary_encoded = Special::encodeAry([
    '<p>This is a test</p>', 
    0.99,
    new stdClass(),
]);

// This string is encoded
// The float is converted to a string
// The object is left as it is

var_dump($ary_encoded);

// ->
// array(3) {
//   [0]=>
//   string(33) "&lt;p&gt;This is a test&lt;/p&gt;"
//   [1]=>
//   string(4) "0.99"
//   [2]=>
//   object(stdClass)#3 (0) {
//   }
// }
