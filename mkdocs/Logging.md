There is no logging system built-in, but only a service that loads an instance
of the [Monolog\Logger](https://github.com/Seldaek/monolog) class.
In order to use this service run the following composer command:

    composer require monolog/monolog

Without any configuration, the default logger writes log messages 
to the file `logs/main.log` file.

If you want to alter the default logger, you can specify this in 
a `Log.php` configuration file. 

```config/Log.php ->```

~~~php
<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Pebble\Path;

// Level
$logger_level = Logger::DEBUG;

// Generate Monolog instance
$logger = new Logger('main');
$base_path = Path::getBasePath();
$logger->pushHandler(new StreamHandler($base_path . '/logs/main.log', $logger_level));
$logger->pushHandler(new StreamHandler('php://stderr', $logger_level));

return [
    'level' => $logger_level,
    'logger' => $logger,
];

~~~

The logger from the above configuration file writes to the default log file, 
but also to `php://stderr`. Let's test it:

```examples/logging/index.php ->```

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\Service\LogService;

// You can get a log instance from AppBase
$log = (new LogService())->getLog();

// Just a message
$log->debug('Debug message');

// Add some more info as an array
$log->debug('Debug message', ['info' => 'Debug info', 'URL' => $_SERVER['REQUEST_URI']]);

// Error message
$log->error('Error message');

// Error message with info as an array
$log->error('Error message', ['info' => 'Error info', 'URL' => $_SERVER['REQUEST_URI']]);
~~~

You may run this example:

    php -S localhost:8000 -t examples/logging

If you visit e.g. [http://localhost:8000](http://localhost:8000)

You will get a couple of log message in `logs/main.log`, 
and because we use the log instance from `config/Log.php` class 
the same messages are written to `php://stderr`.

For more information visit [Monolog\Logger](https://github.com/Seldaek/monolog).

<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/300-Logging.md'>Edit this page on GitHub</a>