This part show other small classes you can use:

### Captcha

The `Pebble\Captcha` class uses the [gregwar/captcha](https://github.com/Gregwar/Captcha) package.
You need to install this package, e.g. using composer: 

    composer require diversen/captcha

Usage:

```examples/captcha/index.php ->```

~~~php
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

~~~

### CSRF

The `Pebble\CSRF` is a protection against **Cross-site request forgery**. You may read more about 
[Cross-site request forgery](https://en.wikipedia.org/wiki/Cross-site_request_forgery) on wikipedia.

For implementation you may look at [this stackoverflow answer](https://stackoverflow.com/a/31683058/464549) 

Usage:

```examples/csrf/index.php ->```

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\CSRF;

session_start();

$csrf = new CSRF();

/** 
 * Get a token to use in a form
 * 
 * Gets a token to use in a form. E.g. as a hidden input
 * <input name="csrf_token" type="hidden" value="<?=$token?>" />
 * This also sets the token value in $_SESSION['csrf_token'] 
 */
$token = $csrf->getToken();

/**
 * Validate the form
 */

// Explicit specify token to validate
$res = $csrf->validateToken($_POST['csrf_token']);

// If no token is set then $_POST['csrf_token'] will be used as token to validate
$res = $csrf->validateToken();

if ($res) {
    echo "Validated";
    // Do something useful
} else {
    echo "Not validated";
    // Give an error
}

~~~

### Cookie

The `Pebble\Cookie` makes it easy to set a cookie from configuration:

Let's say you have some configuration for setting a cookie: 

```config/Auth.php ->```

~~~php
<?php

return
[
    'cookie_path' => '/',
    'cookie_secure' => true,
    'cookie_domain' => $_SERVER['SERVER_NAME'] ?? '',
    'cookie_http' => true
];
~~~

Now you can set a cookie like this: 

```examples/cookie/index.php ->```

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\Cookie;
use Pebble\Service\ConfigService;

// Read the config to use with the cookie
$config = (new ConfigService())->getConfig();
$cookie_settings = $config->getSection('Auth');

// Create cookie object
$cookie = new Cookie($cookie_settings);

// Cookie will last for 10 seconds
if (isset($_COOKIE['test'])) {
    echo "Value of the cookie 'test': " .  $_COOKIE['test'];
} else {
    $cookie->setCookie('test', rand(), 10);
    echo "Random 'test' cookie value has been set. Will exist for 10 seconds";
}

~~~

You may run this example: 

    php -S localhost:8000 -t examples/cookie/

### Session

The `Pebble\Session` is for setting the configuration of the session. 

You will need a configuration file like this: 

```config/SessionShort.php ->```

~~~php
<?php

return [
    'lifetime' => 10, // Seconds
    'path' => '/',
    // prefix with a dot to use all domains e.g. .php.net
    'domain' => $_SERVER['SERVER_NAME'] ?? '',
    'secure' => true, //
    'httponly' => true,
];

~~~

Now we define our session from the configuration:

```examples/session/index.php ->```

~~~php
<?php

require "../../vendor/autoload.php";

use Pebble\Service\ConfigService;
use Pebble\Session;

$config = (new ConfigService())->getConfig();
$session_config = $config->getSection('SessionShort');

Session::setConfigSettings($session_config);
session_start();

if (!isset($_SESSION['started'])) {
    $_SESSION['started'] = random_int(0, 10); 
    echo "Session started has been set";
} else {
    
    echo "Value of \$_SESSION['started'] = $_SESSION[started]";
}



~~~

### SessionTimed

The `Pebble\SessionTimed` will set a SESSION variable that will run out 
after exactly **a determined number of seconds**, regardless of the general lifetime of the SESSION 
cookie: 

```examples/session_timed/index.php ->```

~~~php
<?php

require "../../vendor/autoload.php";

use Pebble\SessionTimed;

session_start();

$session_timed = new SessionTimed();
if (!$session_timed->getValue('test')) {
    echo "Setting new random int as session variable<br>";
    echo "Regardless of the general session's lifetime<br />";
    echo "The random value will exist for 5 seconds";
    $session_timed->setValue('test', random_int(0, 1000000), 5);
} else {
    echo "This is the random int: " . $session_timed->getValue('test');
}






~~~

### File

The `Pebble\File` class contains only one method which will get all files recursively 
from a single directory (excluding '.' and '..'): 

```examples/file/index.php ->```

~~~php
<?php

include_once "../../vendor/autoload.php";

use Pebble\File;
use Pebble\Path;

// All config/ files
$files = File::dirToArray(Path::getBasePath() . '/config');
var_dump($files);
~~~

### DBCache

A simple key / value cache. Usage: 

```examples/dbcache/index.php ->```

~~~php
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
~~~

Run the example:

    php -S localhost:8000 -t examples/dbcache

### ExceptionTrace

The `Pebble\ExceptionTrace` class gets info from an Exception as a string:

```examples/exceptiontrace/index.php ->```

~~~php
<?php

require "../../vendor/autoload.php";

use Exception;
use Pebble\ExceptionTrace;

try {
    throw new Exception('This went horrible wrong');
} catch (Exception  $e) {
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
}

// This following message will be printed:
//
// Message: This went horrible wrong
// In: /home/dennis/pebble-framework-docs/examples/exceptiontrace/index.php (9)
// Trace: 
// #0 {main}

~~~

### Headers

The `Pebble\Headers` class has one method, that will redirect from http to https
if the client is not already using https. 

```examples/headers/index.php ->```

~~~php
<?php

require "../../vendor/autoload.php";

use Pebble\Headers;

// Redirect current url to https
Headers::redirectToHttps();
~~~

### JSON

The `Pebble\JSON` class has one method which is a slightly modified version of `json_encode`. 

It adds the header `Content-Type: application/json`, and it throws exception on 
encoding error. It is also possible to use a debug mode. 

```examples/json/index.php ->```

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\JSON;

// Add debug info to the JSON array
JSON::$debug = false; // Default is false

// Send JSON header and response
echo JSON::response(['some value', 'some other value']);

// Outputs -> 
// {"0":"some value","1":"some other value","__POST":[],"__GET":[]}

// Outputs -> (IF JSON::$debug is false)
// ["some value","some other value"]
~~~

### Path

The `Pebble\Path` class has one method which gives you the path where `vendor/` is locatated:

```examples/path/index.php ->```

~~~php
<?php

require "../../vendor/autoload.php";

use Pebble\Path;

echo Path::getBasePath();

// print something like: 
// -> /home/dennis/pebble-framework-docs
~~~

### Random

The `Pebble\Random` class has a single method that gives you a truly random string:

```examples/random/index.php ->```

~~~php
<?php

require "../../vendor/autoload.php";

use Pebble\Random;

echo Random::generateRandomString(16);
// print something like (2*16) hex chars: 
// -> 3108769d59468a6f6507a663b2fba9a4
~~~

### Server

The `Pebble\Server` class has a single method that gives you both scheme and host of 
your server:

```examples/server/index.php ->```

~~~php
<?php

require "../../vendor/autoload.php";

use Pebble\Server;

$scheme_and_host = (new Server())->getSchemeAndHost();
echo $scheme_and_host;

// Prints something like this
// -> http://localhost:8000


~~~

<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/930-Misc.md'>Edit this page on GitHub</a>