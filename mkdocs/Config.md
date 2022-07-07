All files in the `config/` folder are read first, 
when creating the config instance. 

There is a couple of configuration files in this directory, but
we will just focus on the `App.php` file. 

Any configuration file being used, should return an assoc array with key names and values, 
and that is what the `config/App.php` file does. 

```config/App.php ->```

~~~php
<?php

return [
    'env' => 'live',
    'secret' => 'A secret!',
];
~~~

Then all files in the `config-locale/` directory are read.
Any values in these files will override the values found in `config`.

```config-locale/App.php ->```

~~~php
<?php

return [
    'env' => 'dev',
];

~~~

Therefore: In the `config-locale` folder you should keep locale settings. 
These settings will override the general settings in `config`. 

Let's use the `App.php` configuration in an example, where we will be reading
some configuration values: 

```examples/config/index.php ->```

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppBase;
use Pebble\Service\ConfigService;

// Get the service from the ConfigService class
$config = (new ConfigService())->getConfig();

// Or get the ConfigService instance from the AppBase class
$config = (new AppBase())->getConfig();

// Is dev. Because config-locale/ overrides config/
if ($config->get('App.env')) {
    echo  "Env is: " . $config->get('App.env') . "<br />";
    // -> Env is: dev
} else {
    echo "Env is not defined!<br />";
}

// No override in config-locale/ so we just get 'A secret!'
echo "What is the secret. It is: " . $config->get('App.secret') . "<br />";
// -> What is the secret. It is: A secret!

// You can get a complete configuration section like this:
echo "var_dump all settings in the section App (App.php):<br />";
var_dump($config->getSection('App'));
// -> array(2) { ["env"]=> string(3) "dev" ["secret"]=> string(9) "A secret!" }


~~~

You can run this example like this:

    php -S localhost:8000 -t examples/config

And visit [http://localhost:8000/](http://localhost:8000/)


<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/400-Config.md'>Edit this page on GitHub</a>