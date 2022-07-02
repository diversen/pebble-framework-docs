<?php

require_once "../../vendor/autoload.php";

use Pebble\Captcha;

session_start();
$captcha = new Captcha();

// Ouputs a captcha image
// And sets $_SESSION['captcha_phrase']
// Maybe output this image somewhere in a controller
$captcha->outputImage();

// // In another controller method you would validate this image
// // Maybe in a form validation 
// if ($captcha->validate($phrase)) {
//     echo "The phrase is OK";
//     // Do something
// } else {
//     echo "The phrase is NOT OK";
// }
