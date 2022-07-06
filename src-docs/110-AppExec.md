The above router class showed that it is quite easy to make an
execution environment, where any errors thrown will be caught. 

There is such an app execution environment built-in and it is easy to
use, but you will need the [Monolog](https://github.com/Seldaek/monolog) package. This is used for writing log messages:

    composer require monolog/monolog

Now let's try and use the class `Pebble\AppExec` in the following example:

<!-- include: examples/app_exec/index.php -->

You may add your own error controller.

The only public method in `Pebble\App\StdErrorController` is `render`. 
Your error controller will need to have a `render` method. This method
has a single `param` which is an `Exception`.   

The directories `config` and `config-locale` MUST exist at the same level
as the composer `vendor` dir. The `logs` dir will be created automatically
when the first log message is written. This is also created at the same level
as the `vendor` dir. So this is the directory structure: 

    config/
    config-locale/
    vendor/
    logs/

Run the example:

    php -S localhost:8000 -t examples/app_exec/

You may now visit e.g. [http://localhost:8000/](http://localhost:8000/)

And you will get a `hello world!`. 

You can also visit a route that does not exist: 
[http://localhost:8000/does/not/exists](http://localhost:8000/does/not/exists)

And you will get an error message and a stack trace. 

If you open the log file: 

    more logs/main.log

You will notice that a log message has been appended. 
