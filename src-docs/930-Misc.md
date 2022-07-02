This part show other small classes you can use:

### Captcha

The `Pebble\Captcha` class uses the [gregwar/captcha](https://github.com/Gregwar/Captcha) package.
You need to install this package, e.g. using composer: 

    composer require gregwar/captch

Usage:

<!-- include: examples/captcha/index.php -->

### CSRF

The `Pebble\CSRF` is a protection against **Cross-site request forgery**. You may read more about 
[Cross-site request forgery](https://en.wikipedia.org/wiki/Cross-site_request_forgery) on wikipedia.

For implementation you may look at [this stackoverflow answer](https://stackoverflow.com/a/31683058/464549) 

Usage:

<!-- include: examples/csrf/index.php -->

### Cookie

The `Pebble\Cookie` makes it easy to set a cookie from configuration:

Let's say you have some configuration for setting a cookie: 

<!-- include: config/Auth.php -->

Now you can set a cookie like this: 

<!-- include: examples/cookie/index.php -->

You may run this example: 

    php -S localhost:8000 -t examples/cookie/

### Session

The `Pebble\Session` is for setting the configuration of the session. 

You will need a configuration file like this: 

<!-- include: config/SessionShort.php -->

Now we define our session from the configuration:

<!-- include: examples/session/index.php -->

### SessionTimed

The `Pebble\SessionTimed` will set a SESSION variable that will run out 
after exactly **a determined number of seconds**, regardless of the general lifetime of the SESSION 
cookie: 

<!-- include: examples/session_timed/index.php -->

### File

The `Pebble\File` class contains only one method which will get all files recursively 
from a single directory (excluding '.' and '..'): 

<!-- include: examples/file/index.php -->

### DBCache

A simple key / value cache. Usage: 

<!-- include: examples/dbcache/index.php -->

Run the example:

    php -S localhost:8000 -t examples/dbcache

### ExceptionTrace

The `Pebble\ExceptionTrace` class gets info from an Exception as a string:

<!-- include: examples/exceptiontrace/index.php -->

### Headers

The `Pebble\Headers` class has one method, that will redirect from http to https
if the client is not already using https. 

<!-- include: examples/headers/index.php -->

### JSON

The `Pebble\JSON` class has one method which is a slightly modified version of `json_encode`. 

It adds the header `Content-Type: application/json`, and it throws exception on 
encoding error. It is also possible to use a debug mode. 

<!-- include: examples/json/index.php -->

### Path

The `Pebble\Path` class has one method which gives you the path where `vendor/` is locatated:

<!-- include: examples/path/index.php -->

### Random

The `Pebble\Random` class has a single method that gives you a truly random string:

<!-- include: examples/random/index.php -->

### Server

The `Pebble\Server` class has a single method that gives you both scheme and host of 
your server:

<!-- include: examples/server/index.php -->