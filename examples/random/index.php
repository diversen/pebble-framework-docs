<?php

require "../../vendor/autoload.php";

use Pebble\Random;

echo Random::generateRandomString(16);
// print something like (2*16) hex chars: 
// -> 3108769d59468a6f6507a663b2fba9a4