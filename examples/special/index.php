<?php

require_once "../../vendor/autoload.php";

use Pebble\Special;

// Encode a single string
echo Special::encodeStr('<p>This is a test</p>') . "\n";
// -> &lt;p&gt;This is a test&lt;/p&gt;

// Encode an array. 
// It will work recursively if the array contains other arrays
$ary_encoded = Special::encodeAry([
    '<p>This is a test</p>', 
    0.99,
    true,
    'ary' => ['New array <p>Test</p>'],
    new stdClass(),
]);

// This string is encoded
// The float is converted to a string
// The boolean is left as it is
// The array is encoded
// The object is left as it is

echo "<pre>";
var_dump($ary_encoded);
echo "</pre>";
// ->
// array(5) {
//     [0]=>
//     string(33) "&lt;p&gt;This is a test&lt;/p&gt;"
//     [1]=>
//     string(4) "0.99"
//     [2]=>
//     bool(true)
//     ["ary"]=>
//     array(1) {
//         [0]=>
//         string(33) "New array &lt;p&gt;Test&lt;/p&gt;"
//     }
//     [3]=>
//     object(stdClass)#3 (0) {
//     }
// }
  
