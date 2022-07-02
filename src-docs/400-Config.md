All files in the [config/](config) folder are read first, 
when creating the config instance. 

There is a couple of configuration files in this directory, but
we will just focus on the `App.php` file. 

Any configuration file being used, should return an assoc array with key names and values, 
and that is what the `config/App.php` file does. 

<!-- include: config/App.php -->

Then all files in the [config-locale/](config-locale) directory are read.
Any values in these files will override the values found in `config`.

<!-- include: config-locale/App.php -->

Therefore: In the `config-locale` folder you should keep locale settings. 
These settings will override the general settings in `config`. 

Let's use the `App.php` configuration in a simple example, where we will be reading
some configuration values: 

<!-- include: examples/config/index.php -->

You can run this example like this:

    php -S localhost:8000 -t examples/config

And visit [http://localhost:8000/](http://localhost:8000/)
