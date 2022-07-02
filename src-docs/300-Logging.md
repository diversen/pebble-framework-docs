There is no logging system built-in, but only a service that loads an instance
of the [Monolog\Logger](https://github.com/Seldaek/monolog) class. 
In order to use this service run the following composer command:

    composer require monolog/monolog

Without any configuration, the default logger writes log messages 
to the file `logs/main.log` file.

If you want to alter the default logger, you can specify this in 
the Log.php configuration file. 

<!-- include: config/Log.php -->

The logger from the configuration file writes to the default log file, 
but also to `php://stderr`. Let's test it:

<!-- include: examples/logging/index.php -->

You may run this example:

    php -S localhost:8000 -t examples/logging

If you visit e.g. http://localhost:8000

You will get a couple of log message in `logs/main.log`, 
and because we use the log instance from `config/Log.php` class 
the same messages are written to `php://stderr`.
