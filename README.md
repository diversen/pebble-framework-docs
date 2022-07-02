## Setup



This is the documentation for the `Pebble Framework`. If you clone this repo, 
you can easily run all code examples: 

    git clone https://github.com/diversen/pebble-framework-docs.git 
    cd pebble-framework-docs
    composer install

Use as a dependency in a project:

    composer require diversen/pebble-framework


## Router

* [Defining routes](#defining-routes)
* [Error handling](#error-handling)
* [Middleware](#middleware)


### Defining routes

Routes are defined in controller classes, which are then connected to the router.
When the router receives a request it checks if there is a valid route
in any of the connected controller classes.  

The router is looking for the tags `route` and `verbs` in the comments 
of the controller classes.  

A simple controller example could look like this:

[src/SimpleHomeController.php](src/SimpleHomeController.php)

~~~php
<?php

namespace App;

class SimpleHomeController {

    /**
     * @route /
     * @verbs GET,POST
     */
    public function index() {
        echo "Hello world!";
    }

    /**
     * @route /user/:username
     * @verbs GET
     */
    public function userGreeting(array $params) {
        echo "Hello world $params[username]!";
    } 
}

~~~

The above route `/` will accept the verbs GET and POST. The route
will dispatch the method `index`.

The second route `/user/:username` will dispatch the method `userGreeting`. 
This method transforms the second URL segment into a string parameter, 
which the controller method can use. This route only accepts GET requests.  

The routes can also be made a bit more complex, like `@route /user/:username/actions/:action`
If this route is matched, then the `$params` array will contain the keys `username` and `action`.

Let's connect the above `SimpleHomeController` class to a router instance in an index.php file: 

[examples/router_simple/index.php](examples/router_simple/index.php)

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;

// Init
$router = new Router();

// Add the controller class name to the router
$router->addClass(App\SimpleHomeController::class);

// Run the application
$router->run();
~~~

Run the above example:

    php -S localhost:8000 -t examples/router_simple

If you visit [http://localhost:8000](http://localhost:8000),
you should receive a response from the server saying `hello world!`

If you visit http://localhost:8000/user/helen,
you should receive a response saying `Hello world helen!`

### Error handling

If you visit a route which is not defined, you may get a 500 error without any useful message
(This depends on your server configuration). 

We will make a setup in order to catch all errors. This will also 
deliver a better user experience:

[examples/router_error/index.php](examples/router_error/index.php)

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;
use Pebble\Exception\NotFoundException;
use Pebble\ExceptionTrace;
use Pebble\App\AppBase;

// The app base class is used to provide some basic utilities to the app.
$app_base = new AppBase();

// All errors and notices will be thrown as exceptions
$app_base->setErrorHandler();

try {

    $router = new Router();
    $router->addClass(App\SimpleHomeController::class);
    $router->run();
} catch (NotFoundException $e) {

    // You may show a propper '404 Not Found' page here
    echo $e->getMessage();
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
} catch (Throwable $e) {

    // You may show a '500 Internal Server Error' page here
    // This is an application error
    echo $e->getMessage();
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
}

~~~

You may run this example:

    php -S localhost:8000 -t examples/router_error

If you visit http://localhost:8000/does/not/exists 
you will get a message saying `The page does not exist`

You will also get a better trace of the error. 

### Middleware

You can add middleware to you application. Middleware are just `callables` 
which will be called before hitting the controller method. 
You may specify multiple middleware `callables`. 

Middleware are called in the order that they are added to your `Router` instance. 

Middleware `callables` will receive the same `$params` as your controller.

The second parameter of a `callable` is an `object`, which is passed around from middleware to middleware.
Finally it will be sent to the controller method. 

In the controller method the middleware object is also the second parameter.

Here is a controller where both `$params` and `$middleware_object` are used: 

[src/HomeController.php](src/HomeController.php)

~~~php
<?php

namespace App;

class HomeController {
    
    /**
     * @route /user/:username
     * @verbs GET
     */
    public function userGreeting(array $params, object $middleware_object) {
        echo "Hello world $params[username]!<br />";
        echo $middleware_object->message . "<br />";

        // Note: You can always get the current route from the router if you need to. 
        echo "Current route is: " . \Pebble\Router::getCurrentRoute();
    }   
}

~~~

Then create your application like this: 

[examples/router_middleware/index.php](examples/router_middleware/index.php)

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;
use Pebble\Exception\NotFoundException;
use Pebble\ExceptionTrace;

try {

    $router = new Router();
    $router->addClass(App\HomeController::class);

    function middle_ware_1 ($params, $middleware_object) {
        $middleware_object->message = 'From middle_ware_1';
    }

    function middle_ware_2 ($params, $middleware_object) {
        $middleware_object->message = 'From middle_ware_2';
    }

    // Connect the middleware
    $router->use('middle_ware_1');
    $router->use('middle_ware_2');

    // You may set a middleware class which the middleware object will be created from
    // Otherwise it is just a stdClass the object will be created from
    // $router->setMiddlewareClass(App\MiddlewareClass::class);

    $router->run();
} catch (NotFoundException $e) {

    // You may show a propper '404 Not Found' page here
    echo $e->getMessage();
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
} catch (Throwable $e) {

    // You may show a '500 Internal Server Error' page here
    // This is an application error
    echo $e->getMessage();
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";
}

~~~

You can run this example using:

    php -S localhost:8000 -t examples/router_middleware

If you visit http://localhost:8000/user/helen
you should get the following response: 

    Hello world helen!
    From middle_ware_2
    Current route is: /user/:username


## AppExec



The above router class showed that it is quite easy to make an
execution environment, where any errors thrown will be caught. 

There is such an app execution environment built-in and it is easy to
use. Let's try and use the class `Pebble\AppExec` in the following example:

[examples/app_exec/index.php](examples/app_exec/index.php)

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppExec;
use Pebble\App\AppBase;
use Pebble\App\StdErrorController;
use Pebble\Router;

$app_exec = new AppExec();

// This is the default error controller. You may set your own error controller
// $app_exec->setErrorController(StdErrorController::class);

// Create an app to be executed. It does not need to extend AppBase
// But AppBase has some nice utility methods
class MyApp extends AppBase {

    public function run() {

        // Throw on all errors and notices
        $this->setErrorHandler();

        $router = new Router();
        $router->addClass(App\SimpleHomeController::class);
        $router->run();

    }
}

$app_exec->setApp(MyApp::class);
$app_exec->run();

~~~

You may add your own error controller.

The only public method in `Pebble\StdErrorController` is `render`. 
Your own error controller will need to have a `render` method. This method
has a single `param` which is an `Exception`.   

The directories `config` and `config-locale` MUST exist a the same level
as the composer `vendor` dir. The `logs` dir will be created automatically
when the first log message is written. This is also created at the same level
as the `vendor` dir. So this is the directory structure: 

    config/
    config-locale/
    vendor/
    logs/

You will need the [Monolog](https://github.com/Seldaek/monolog) package. 
This is used for writing the log messages:

    composer require monolog/monolog

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


## Special



The `Pebble\Special` class if used to encode HTML entities:

[examples/special/index.php](examples/special/index.php)

~~~php
<?php

require_once "vendor/autoload.php";

use Pebble\Special;

// Encode a string
echo Special::encodeStr('<p>This is a test</p>') . "\n";
// -> &lt;p&gt;This is a test&lt;/p&gt;

// Encode an array. It will work recursively if the array contains other arrays
$ary_encoded = Special::encodeAry([
    '<p>This is a test</p>', 
    0.99,
    new stdClass(),
]);

// This string is encoded
// The float is converted to a string
// The object is left as it is

var_dump($ary_encoded);

// ->
// array(3) {
//   [0]=>
//   string(33) "&lt;p&gt;This is a test&lt;/p&gt;"
//   [1]=>
//   string(4) "0.99"
//   [2]=>
//   object(stdClass)#3 (0) {
//   }
// }

~~~

## Template



The `Pebble\Template` class is used for creating secure HTML templates. It uses the
`Pebble\Special` class for encoding the template variables. 

Let's create a main page template with the variables `$title` and `$content` in the `src/templates` dir.  
This is the dir where all templates are placed for this project. 

[src/templates/main.php](src/templates/main.php)

~~~php
<!DOCTYPE html>
<html>

<head>
    <title><?= $title ?></title>
</head>

<body>

    <h1>
        <?= $title ?>
    </h1>

    <p>
        <?= $content ?>
    </p>

</body>

</html>
~~~

We will also create a page template for showing content. We create some paragraphs
and then we loop over each one of them.

[src/templates/page.php](src/templates/page.php)

~~~php
<h3>Hi <?=$username?></h3>

<p>Here is a bunch of paragraphs just for you!</p>

<?php

foreach ($paragraphs as $paragraph): ?>
<p><?=$paragraph?></p>
<hr>
<?php

endforeach;
~~~

We add a new controller class called ` TemplateTest` in the `src` dir. 

[src/TemplateTest.php](src/TemplateTest.php)

~~~php
<?php

namespace App;

use Pebble\Template;

class TemplateTest {

    /**
     * @route /user/:username
     * @verbs GET
     */
    public function userGreeting(array $params, object $middle_ware) {
        
        $variables['title'] = 'Greeting with paragraphs'; 
        $variables['username'] = $params['username'];
        $variables['paragraphs'] = [
            'Hi <w><o><o> ' . $params['username'] . '<o><o><h> !', 
            'Nice day today!', 
            'Did they build a wall?', 
            'No, they build a dam!'
        ];
        
        // All the variables with HTML specialchars will be auto-encoded, 
        // They are safe to output to the client
        $variables['content'] = Template::getOutput('templates/page.php', $variables);

        // All variables are already encoded, 
        // Therefor we render this template without encoding (renderRaw)
        Template::renderRaw('templates/main.php', $variables);
        
    } 
}

~~~

Now we can tie it all together in our `index.php` file

[examples/template/index.php](examples/template/index.php)

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\Router;
use Pebble\App\AppBase;
use Pebble\App\AppExec;

class MyApp extends AppBase {

    public function run () {

        $this->setErrorHandler();
        
        // Add src to include path 
        // Then templates are loaded without adding 'src' to the path
        $this->addSrcToIncludePath();

        $router = new Router();
        $router->addClass(App\TemplateTest::class);
        $router->run();

    }
}

$app_exec = new AppExec();
$app_exec->setApp(MyApp::class);
$app_exec->run();

~~~

Run the application:

    php -S localhost:8000 -t examples/template

Visit a route that does not exist and you will get an error, e.g: http://localhost:8000/does/not/exist

Or visit a route that exists: http://localhost:8000/user/Helena


## Logging



There is no logging system built-in, but only a service that loads an instance
of the [Monolog\Logger](https://github.com/Seldaek/monolog) class. 
In order to use this service run the following composer command:

    composer require monolog/monolog

Without any configuration, the default logger writes log messages 
to the file `logs/main.log` file.

If you want to alter the default logger, you can specify this in 
the Log.php configuration file. 

[config/Log.php](config/Log.php)

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

The logger from the configuration file writes to the default log file, 
but also to `php://stderr`. Let's test it:

[examples/logging/index.php](examples/logging/index.php)

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppBase;
use Pebble\Service\LogService;

// You can get a log instance from AppBase
$log = (new AppBase())->getLog();
$log->debug('Some debug message');

// Or you can get a log instance from LogService
// (It is the same instance you will get)
$log = (new LogService())->getLog();
$log->error('Some error message');
~~~

You may run this example:

    php -S localhost:8000 -t examples/logging

If you visit e.g. http://localhost:8000

You will get a couple of log message in `logs/main.log`, 
and because we use the log instance from `config/Log.php` class 
the same messages are written to `php://stderr`.


## Config



All files in the [config/](config) folder are read first, 
when creating the config instance. 

There is a couple of configuration files in this directory, but
we will just focus on the `App.php` file. 

Any configuration file being used, should return an assoc array with key names and values, 
and that is what the `config/App.php` file does. 

[config/App.php](config/App.php)

~~~php
<?php

return [
    'env' => 'live',
    'secret' => 'A secret!',
];
~~~

Then all files in the [config-locale/](config-locale) directory are read.
Any values in these files will override the values found in `config`.

[config-locale/App.php](config-locale/App.php)

~~~php
<?php

return [
    'env' => 'dev',
];

~~~

Therefore: In the `config-locale` folder you should keep locale settings. 
These settings will override the general settings in `config`. 

Let's use the `App.php` configuration in a simple example, where we will be reading
some configuration values: 

[examples/config/index.php](examples/config/index.php)

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


## DB

* [Docker MySQL](#docker-mysql)
* [Connect](#connect)
* [Test](#test)


The `Pebble\DB` class should work with any database, but in this documentation 
we will stick with the MySQL database. If you don't have access to a MySQL database, 
then it is quite easy to run a docker MySQL server instance. 

### Docker MySQL

Install (run) a MySQL image that will work:

    docker run -p 3306:3306 --name mysql-server -e MYSQL_ROOT_PASSWORD=password -d mysql:5.7

List containers 

    docker container ls

Stop container (mysql-server):

    docker stop mysql-server

Start container (mysql-server) again:

    docker start mysql-server

Remove container (you will need to run the 'run' command again):

    docker rm mysql-server

### Connect

The default `config/DB.php` file should work with above docker settings, where 
the username is `root` and the password is `password`.

Check your database settings: 

[config-locale/DB.php](config-locale/DB.php)

~~~php
<?php

return [
	'url' => 'mysql:host=127.0.0.1;dbname=pebble',
	'username' => 'root',
	'password' => 'password',
];

~~~

Create the database if it does not exist:  

    ./cli.sh db --server-connect
    CREATE DATABASE `pebble`;

### Test

Now we can test all methods. Most of these methods should be self-explanatory. 

[examples/database/index.php](examples/database/index.php)

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppBase;
use Pebble\Service\DBService;
use Pebble\ExceptionTrace;

$app_base = new AppBase();
$app_base->setErrorHandler();

function debug($message) {
    echo "$message<br/>";
}

try {
    
    $db = (new AppBase())->getDb();
    // You could also get the same DB instance by using the service class
    $db = (new DBService())->getDB();
    debug("getDb");

    $db->prepareExecute('DROP TABLE IF EXISTS note');
    debug("prepareExecute. Dropped note table");

    $table = <<<EOF
    CREATE TABLE `note` (
        `id` int NOT NULL AUTO_INCREMENT,
        `entry` text COLLATE utf8mb4_general_ci,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `auth_id` int NOT NULL,
        `public` tinyint(1) DEFAULT '0',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    EOF;  

    $db->prepareExecute($table);
    debug("prepareExecute. Created note table");

    $db->beginTransaction();
    $db->insert('note', ['entry' => 'This is a entry text by user id 1', 'auth_id' => 1]);
    $db->insert('note', ['entry' => 'This is a entry text by user id 2', 'auth_id' => 2]);
    $db->insert('note', ['entry' => 'This is another entry text by user id 1', 'auth_id' => 1]);
    $last_insert_id = $db->lastInsertId();
    $db->commit();
    debug("commit. Commited 3 rows");
    debug("lastInsertId. Last insert ID: $last_insert_id");

    // inTransactionExecute() is a shortcut for beginTransaction() and prepareExecute() and commit()
    // It throws an exception if the transaction fails.
    $last_insert_id = $db->inTransactionExec(function () use ($db) {
        $db->insert('note', ['entry' => 'Another entry by user id 1', 'auth_id' => 1]);
        $db->insert('note', ['entry' => 'Another entry by user id 2', 'auth_id' => 2]);
        $last_insert_id = $db->lastInsertId();
        return $last_insert_id;
    });

    debug("lastInsertId. Last insert ID: $last_insert_id");
    
    $num_rows = $db->getTableNumRows('note', 'id');
    debug("getTableNumRows. $num_rows rows in table (counting 'id')");

    $row = $db->getOne('note', ['auth_id' => '1']);
    debug("getOne. Got row with ID '$row[id]' and entry '$row[entry]'");
    
    $rows = $db->getAll('note', ['auth_id' => 1]);
    foreach($rows as $row) {
        debug("getAll. Got row with ID '$row[id]' and entry '$row[entry]'");
    }

    // This gives the same rows as above, but with option for 'order' and 'limit'
    $rows = $db->getAllQuery('SELECT * FROM note', ['auth_id' => '1'], ['id' => 'ASC'], [0, 10]);
    foreach($rows as $row) {
        debug("getAllQuery. Got row with ID '$row[id]' and entry '$row[entry]'");
    }

    $db->update('note', ['entry' => 'This is the UPDATED entry text by user id 1'], ['id' => 1]);
    debug("updated. Updated row with ID '$row[id]'");
    $row = $db->getOneQuery('SELECT * FROM note', ['auth_id' => 1], ['updated' => 'DESC']);
    debug("getOneQuery. Got updated row with ID '$row[id]' and updated entry '$row[entry]'");

    // Use any SQL for getting a single row. Positional parameters
    $row = $db->prepareFetch("SELECT * FROM note WHERE id = ?", [1]);
    debug("prepareFetch (positional parameters). Got row with ID '$row[id]' and entry '$row[entry]'");

    // Use any SQL for getting multiples row. Positional parameters
    $rows = $db->prepareFetchAll("SELECT * FROM note WHERE id >= ?", [1]);
    foreach($rows as $row) {
        debug("prepareFetchAll (positional parameters). Got row with ID '$row[id]' and entry '$row[entry]'");
    }

    // Use any SQL for getting multiples row. Named parameters
    $rows = $db->prepareFetchAll("SELECT * FROM note WHERE id >= :id", ['id' => 1]);
    foreach($rows as $row) {
        debug("prepareFetchAll (named parameters). Got row with ID '$row[id]' and entry '$row[entry]'");
    }

    $db->delete('note', ['id' => 1] );
    debug("Deleted row with id = 1");

    $affected_rows = $db->rowCount();
    debug("rowCount (affected rows): $affected_rows");

    // Get dbh database handle
    $db->getDbh();
    
    // Get LIMIT SQL string
    $limit = $db->getLimitSql([0, 10]);
    debug("getLimitSql. $limit");

    // Get order SQL
    $order = $db->getOrderBySql(['id' => 'ASC', 'username' => 'DESC']);
    debug("getOrderBySql. $order");

    // Get where SQL
    $where_sql = $db->getWhereSql(['auth_id ' => 7, 'birthday' => '1972-02-25']);
    debug("getOrderBySql. $where_sql");

} catch (Exception $e) {
    debug("Error: " . $e->getMessage());
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";

}


~~~

You may run the example:

    php -S localhost:8000 -t examples/database

And then visit [http://localhost:8000/](http://localhost:8000/)


## Migration



Migration creates or updates your database schema. 

The `.migration` file will keep track of the current schema version, 
so that the migration system knows what to execute.  

The `up` migrations are placed in [migrations/up/](migrations/up)

Let's load some SQL into the database. The database statements in `0001.sql` will make it possible
to create users and to check users against an ACL system. The `0002.sql` creates a `note` table.

There is a corresponding `down` folder for migrating down. This folder
holds all the statements that will *undo* the up migrations. 

The down migrations are placed in [migrations/down/](migrations/down)

Let's create a command for running the up migration: 

[examples/migration/up.php](examples/migration/up.php)

~~~php
<?php

include_once "vendor/autoload.php";

use Pebble\Service\MigrationService;
use Pebble\App\AppBase;

// Get migration instance using service class
$migrate = (new MigrationService())->getMigration();

// Or use AppBase class
$migrate = (new AppBase())->getMigration();

// This will migrate both SQL files 0001.sql and 0002.sql
// Unless they already have been migrated
$migrate->up(2);

// This would also migrate both version up
// $migrate->up();
~~~

Run the command from a terminal: 

    php examples/migration/up.php

You `.migration` file will now have version `2`. 

Let's also create a command for running the down migration: 

[examples/migration/down.php](examples/migration/down.php)

~~~php
<?php

include_once "vendor/autoload.php";

use Pebble\Service\MigrationService;
use Pebble\App\AppBase;

// Get migration instance using service class
$migrate = (new MigrationService())->getMigration();

// Or use AppBase class
$migrate = (new AppBase())->getMigration();

// This will migrate both SQL files 0001.sql and 0002.sql
// Unless they already have been migrated
$migrate->down();

// You could also use
// $migrate->down(0);

// Migrate down to version 1. Drops tables in 0002.sql
// $migrate->down(1);

// Migrate down to version 0 Drops tables in 0001.sql
// $migrate->down(0);
~~~

Migrate down from a terminal:

    php examples/migration/down.php

Your `.migration` file is now removed (version 0)

Migrate up again as we will use the new database schema when using the 
`Pebble\Auth` class.  

## Auth



The Auth instance is created using a `Pebble\DB` object and an array of `cookie settings`. 

This is what the `Auth` cookie configuration, which we will use, looks like:

[config/Auth.php](config/Auth.php)

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

And now let's use our newly created Auth object in an example: 

[examples/auth/index.php](examples/auth/index.php)

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\Service\AuthService;
use Pebble\App\AppBase;

$app_base = new AppBase();
$app_base->setErrorHandler();

// Get auth instance. Use the service class
$auth = (new AuthService())->getAuth();

// Or use AppBase class
$auth = (new AppBase())->getAuth();

// Just for printing what is going on
function debug($message) {
    echo $message . "<br />";    
} 

// Generate a User
$email = 'test@tester.com';
$password = 'strong1234';

$user = $auth->getByWhere(['email' => $email]);

if (!$user) {
    try {
        $auth->create($email, $password);
        debug("create. User with email $email created");
    } catch (Exception $e) {
        debug($e->getMessage());
    }
}

if ($auth->isVerified($email)) {
    debug("isVerified. User account is verified");

    if (!$auth->isAuthenticated()) {
        debug("isAuthenticated. User is not authenticated. User has no valid auth cookie");
        
        // Wrong password
        $row = $auth->authenticate($email, 'wrong_password');
        if (!$row) {
            debug("authenticate. Error authenticating. Wrong email og password");
        }

        // Correct password
        $row = $auth->authenticate($email, $password);
        if ($row) {
            debug("authenticate. User with email $row[email] is authenticated. ");
            $auth->setCookie($row, 10);
            debug("setCookie. Auth cookie has been set. User will be in session for the next 10 seconds");
            // $auth->setCookie($row, 0); 
            // 0 or null will be a session cookie. This expires when the browser closes. 
            
        }
    } else {
        $auth_id = $auth->getAuthId();
        debug("isAuthenticated. User's auth ID: $auth_id");
    }

} else {
    $row = $auth->getByWhere(['email' => $email]);
    
    // User account will be verified by passing the 'random' value connection to the account 
    $auth->verifyKey($row['random']);
    debug("User has been verified");
}

// Update a user password
// $auth->updatePassword($row['id'], 'new super password');

// Log user out of all devices
// $auth->unlinkAllCookies($row['id']);

// Log user out of this device
// $auth->unlinkCurrentCookie();

~~~

You may run the Auth example:

    php -S localhost:8000 -t examples/auth

## ACL



An access control list (ACL) is a list of rules that specifies which users or systems 
are granted or denied access to a particular object or system resource.

The ACL class extends the Auth class so it is possible to use all public
methods found in the Auth class. 

An ACL right consist of an `entity`, `entity_id`, `right`, and `auth_id`. 
The `entity` could be a database table named  **note**. The `entity_id` could be the primary ID
of the note table. The `right` could be `read` or `write`, and the `auth_id` is probably a 
logged in user's `auth_id`.   

Let's test the ACL object in a controller. 

[src/ACLTestController.php](src/ACLTestController.php)

~~~php
<?php

namespace App;

use Pebble\Service\ACLService;
use Pebble\App\AppBase;
use Exception;

class ACLTestController
{

    private $acl;
    private $rights = [];
    public function __construct()
    {
        $this->acl = (new ACLService())->getACL();

        // Or
        $this->acl = (new AppBase())->getACL();

        // Under normal circumstances you would receive a auth_id 
        // from the ACL object using `$this->acl->getAuthId();`
        // When the user is logged in. 
        $this->rights =
            [
                'entity' => 'note',
                'entity_id' => 42,
                'right' => 'read',
                'auth_id' => 1,
            ];
    }


    /**
     * @route /rights/add
     * @verbs GET
     */
    public function RightsAdd()
    {
        $this->acl->setAccessRights($this->rights);
        echo "Access rights added";
    }

    /**
     * @route /rights/remove
     * @verbs GET
     */
    public function rightsRemove()
    {
        $this->acl->removeAccessRights($this->rights);
        echo "Access rights removed";
    }

    /**
     * @route /note/read/:id
     * @verbs GET
     */
    public function noteRead(array $params)
    {
        $rights = [
            'entity' => 'note',
            'entity_id' => $params['id'],
            'right' => 'read',
            'auth_id' => 1,
        ];

        try {
            $this->acl->hasAccessRightsOrThrow($rights);
            echo "You can see the secret note 42";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
    }
}

~~~

We execute this controller in our `index.php` file: 

[examples/acl/index.php](examples/acl/index.php)

~~~php
<?php

require_once "../../vendor/autoload.php";

use App\ACLTestController;
use Pebble\App\AppBase;
use Pebble\App\AppExec;
use Pebble\Router;

class TestApp  extends AppBase {
    public function run() {
        $this->setErrorHandler();
        $router = new Router();
        $router->addClass(ACLTestController::class);
        $router->run();
    }
}

$app_exec = new AppExec();
$app_exec->setApp(TestApp::class);
$app_exec->run();

~~~

Run this example using:

    php -S localhost:8000 -t examples/acl

You can now add the access right on http://localhost:8000/rights/add

You can remove it on http://localhost:8000/rights/remove

If the right exists then you may visit http://localhost:8000/note/read/42

But you can never visit http://localhost:8000/note/read/41 (this ID can not be set)


## ACLRole



The `Pebble\ACLRole` class works almost like the `Pebble\ACL` class.  

The ACLRole class extends the ACL class so it is possible to use all public
methods found in the `Pebble\ACL` class and the `Pebble\Auth` class. 

An ACL role consist of a `right` and  a `auth_id`. 
The `right` could be `admin` or `read` and the `auth_id` is probably
the `auth_id` of a logged in user.  

Let's test the ACL object in a controller. 

[src/ACLRoleTestController.php](src/ACLRoleTestController.php)

~~~php
<?php

namespace App;

use Pebble\Service\ACLRoleService;
use Pebble\App\AppBase;
use Exception;

class ACLRoleTestController
{

    private $acl_role;
    private $role = [];
    public function __construct()
    {
        // Get acl role instance using the service class
        $this->acl_role = (new ACLRoleService())->getACLRole();

        // Get acl role instance using the AppBase class
        $this->acl_role = (new AppBase())->getACLRole();

        // Under normal circumstances you would receive an auth_id 
        // from the ACLRole object using `$this->acl_role->getAuthId();`
        // when the user is in session
        $this->role =
            [
                'right' => 'admin',
                'auth_id' => '1'
            ];
    }

    /**
     * @route /role/add
     * @verbs GET
     */
    public function roleAdd()
    {
        $this->acl_role->setRole($this->role);
        echo "Access role added";
    }

    /**
     * @route /role/remove
     * @verbs GET
     */
    public function roleRemove()
    {
        $this->acl_role->removeRole($this->role);
        echo "Access rights removed";
    }

    /**
     * @route /admin/notes
     * @verbs GET
     */
    public function noteRead(array $params)
    {
        $role = [
            'right' => 'admin',
            // Normally you would use `$this->acl_role->getAuthId();`
            'auth_id' => 1, 
        ];

        try {
            $this->acl_role->hasRoleOrThrow($role);
            echo "You have the admin role. You have access to /admin/notes";
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

~~~

We execute this controller in our `index.php` file: 

[examples/acl_role/index.php](examples/acl_role/index.php)

~~~php
<?php

require_once "../../vendor/autoload.php";

use App\ACLRoleTestController;
use Pebble\App\AppBase;
use Pebble\App\AppExec;
use Pebble\Router;

class TestApp  extends AppBase {
    public function run() {
        $this->setErrorHandler();
        $router = new Router();
        $router->addClass(ACLRoleTestController::class);
        $router->run();
    }
}

$app_exec = new AppExec();
$app_exec->setApp(TestApp::class);
$app_exec->run();

~~~

Run this example using:

    php -S localhost:8000 -t examples/acl_role

You can now add the admin role on http://localhost:8000/role/add

You can remove it on http://localhost:8000/role/remove

If the role exists then you may visit http://localhost:8000/admin/notes


## Flash



Flash messages are messages that are displayed to the client -
usually after some action has been performed. 

If the client has logged in, then it is normal to display 
a message like *Welcome. You are now logged in*.

In the following example we make two routes, `/` and `/click`. 

The `/` route just shows a link, that says *Click me*. When 
clicking this link we navigate to `/click` which sets a flash message
in the `$_SESSION['flash']` variable. 

Then we are redirected back to `/`, where the flash messages are 
displayed and cleared.  

[src/FlashTestController.php](src/FlashTestController.php)

~~~php
<?php

namespace App;

use Pebble\Flash;
use App\AppBase;

$app_base = new AppBase();
$app_base->setErrorHandler();
$app_base->setIncludePath();

class FlashTestController
{
    private $flash;
    function __construct()
    {
        $this->flash = new Flash();
    }

    /**
     * @route /
     * @verbs GET
     */
    public function index()
    {
        $flash_str = '';
        $flashes = $this->flash->getMessages();
        foreach ($flashes as $flash) {
            $flash_str .= $flash['message'] . " ($flash[type]) ";
        }

        $content = '<div><a href="/click">Click me</a></div>';
        if ($flash_str) {
            $content .= "<div style='background-color: lightgreen'>$flash_str</div>";
        }
        
        echo $content;
    }

    /**
     * @route /click
     * @verbs GET
     */
    public function click(array $params, Object $object)
    {
        $random = rand(0, 10);
        $message = "Your clicked a link and got this random number: $random";
        $this->flash->setMessage($message, 'info', ['flash_remove' => true]);
        header("Location: /");
    }
}

~~~

We execute this controller in our `index.php` file: 

[examples/flash/index.php](examples/flash/index.php)

~~~php
<?php

require_once "../../vendor/autoload.php";

use App\FlashTestController;
use Pebble\App\AppBase;
use Pebble\App\AppExec;
use Pebble\Router;

class TestApp  extends AppBase {
    public function run() {
        $this->setErrorHandler();

        // Start session as flash message uses session
        $this->sessionStart();
        
        $router = new Router();
        $router->addClass(FlashTestController::class);
        $router->run();
    }
}

$app_exec = new AppExec();
$app_exec->setApp(TestApp::class);
$app_exec->run();

~~~

Run the example: 

    php -S localhost:8000 -t examples/flash

You may then visit http://localhost:8000



## SMTP



The `Pebble\SMTP` class uses the following two packages `phpmailer/phpmailer` and `erusev/parsedown` 

In order to use the SMTP mail system you will have to require the following packages: 

    composer require erusev/parsedown
    composer require phpmailer/phpmailer

This is an example of the configuration used for the SMTP instance: 

[config/SMTP.php](config/SMTP.php)

~~~php
<?php

// Configuration for PHPMailer
return [
    'DefaultFrom' => 'mail@10kilobyte.com',
    'DefaultFromName' => 'Time Manager',
    'Host' => 'smtp-relay.sendinblue.com',
    'Port' => 587,
    'SMTPAuth' => true,
    'SMTPSecure' => 'tls',
    'Username' => 'username',
    'Password' => 'password',
    'SMTPDebug' => 0
];

~~~

Now you can send some HTML or Markdown emails: 

[examples/smtp/index.php](examples/smtp/index.php)

~~~php
<?php

require '../../vendor/autoload.php';

use Pebble\SMTP;
use Pebble\Service\ConfigService;

// Get SMTP config array
$config = (new ConfigService())->getConfig();
$smtp_settings = $config->getSection('SMTP');

// Get SMTP instance
$smtp = new SMTP($smtp_settings);

// Some attachements to attach
$paths_to_attachments = [];

// Send text and HTML
$smtp->send(
    'to@mail.com',
    'test subject',
    'Mail content in text',
    '<p>Mail content in HTML</p>',
    $paths_to_attachments
);

// Safe mode on markdown (defaults to true)
$smtp->setSafeMode(true);

// Send markdown. The text content of the email is the raw markdown
$smtp->sendMarkdown(
    'to@mail.com',
    'test subject',
    '### Test markdown',
    $paths_to_attachments = []
);

// Specify both the text and the markdown content
$smtp->sendTextMarkdown(
    'to@mail.com',
    'test subject',
    'Text content',
    '### Markdown content',
    $paths_to_attachments = []
);

// Alter from email
$stmp->setFrom('another+from@mail.com');
$smtp->setFromName('Mr Doe');

~~~




## CLI

* [Example sub-command](#example-sub-command)
* [Create CLI program](#create-cli-program)


The pebble frameworks uses the `diversen/minimal-cli-framework` for making command line programs. 

    composer require diversen/minimal-cli-framework

### Example sub-command

Create a command: 

[src/CliTestCommand.php](src/CliTestCommand.php)

~~~php
<?php

declare(strict_types=1);

namespace App;

use Diversen\ParseArgv;

class CliTestCommand {

    /**
     * Command definition
     */
    public function getCommand() {
        return 
            array (
                // Command help
                'usage' => 'A simple test command. Gives file size in bytes',
                
                // Options to the command
                'options' =>    ['--pretty' => 'Pretty format of bytes. In KB, MB, GB, TB'],

                // Arguments to the command
                'arguments' =>  ['file' => 'File to get size of']
            );
    }

    /**
     * Method for getting size in KB, MB, GB, or TB
     */
    private function size2Byte($size) {
        $units = array('KB', 'MB', 'GB', 'TB');
        $currUnit = '';
        while (count($units) > 0  &&  $size > 1024) {
            $currUnit = array_shift($units);
            $size /= 1024;
        }
        return ($size | 0) . $currUnit;
    }

    /**
     * Run the command
     * @param ParseArgv $args
     */
    public function runCommand(ParseArgv $args) {

        // Check if any arguments
        $file = $args->getArgument(0);
        if (!$file) {
            echo "Specify file" . "\n";
            return 1;
        }

        // Check if argument is a file
        if (!file_exists($file) || !is_file($file) ) {
            echo "File does not exist\n";
            return 1;
        }

        // 
        $size = filesize($file);

        // Check if filesize should be converted
        if ($args->getOption('pretty')) {
            $size = $this->size2Byte($size);
        } else {
            $size.= " Bytes";
        }

        // Print the size of the file
        $res_string = "Size of file: $file is: ";
        echo $res_string . $size . "\n";
        return 0;
    }
}

~~~

### Create CLI program

There is few built-in commands that you can use right away. 

Let's add the  above command to a CLI program with some commands that are included
with the `pebble-framework`. 

[examples/cli/index.php](examples/cli/index.php)

~~~php
<?php declare (strict_types = 1);

require_once "vendor/autoload.php";

use Diversen\MinimalCli;
use Pebble\CLI\User;
use Pebble\CLI\DB;
use Pebble\CLI\Migrate;
use Pebble\CLI\Translate;

use App\CliTestCommand;

$cli = new MinimalCli();
$cli->commands = [
    'user' => new User(),
    'db' => new DB(),
    'migrate' => new Migrate(),
    'translate' => new Translate(),
    'filesize' => new CliTestCommand(),
];


$cli->runMain();

~~~

Run the command, e.g: 

    php examples/cli/index.php filesize --pretty ./README.md

Same as:

    php examples/cli/index.php filesize --pr ./README.md

Get help about the command: 

    php examples/cli/index.php filesize -h

Use the built-in DB command (connect to the database):

    php examples/cli/index.php db --connect




## Misc

* [Captcha](#captcha)
* [CSRF](#csrf)
* [Cookie](#cookie)
* [Session](#session)
* [SessionTimed](#sessiontimed)
* [File](#file)
* [DBCache](#dbcache)
* [ExceptionTrace](#exceptiontrace)
* [Headers](#headers)
* [JSON](#json)
* [Path](#path)
* [Random](#random)
* [Server](#server)


This part show other small classes you can use:

### Captcha

The `Pebble\Captcha` class uses the [gregwar/captcha](https://github.com/Gregwar/Captcha) package.
You need to install this package, e.g. using composer: 

    composer require gregwar/captch

Usage:

[examples/captcha/index.php](examples/captcha/index.php)

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

[examples/csrf/index.php](examples/csrf/index.php)

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

[config/Auth.php](config/Auth.php)

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

[examples/cookie/index.php](examples/cookie/index.php)

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

// Session cookie
// $cookie->setCookie('test', 'test', 0);

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

[config/SessionShort.php](config/SessionShort.php)

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

[examples/session/index.php](examples/session/index.php)

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

[examples/session_timed/index.php](examples/session_timed/index.php)

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

[examples/file/index.php](examples/file/index.php)

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

[examples/dbcache/index.php](examples/dbcache/index.php)

~~~php
<?php

require "../../vendor/autoload.php"; 

use Pebble\Service\DBService;
use Pebble\DBCache;

$db = (new DBService())->getDB();

$cache = new DBCache($db);

// Try to get a result ignoreing max age
// $from_cache = $cache->get('some_key');

// Get a result that is max 100 seconds old
$from_cache = $cache->get('some_key', 10);


// Cache a value

if (!$from_cache) {
    echo "No result<br />";
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

[examples/exceptiontrace/index.php](examples/exceptiontrace/index.php)

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

[examples/headers/index.php](examples/headers/index.php)

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

[examples/json/index.php](examples/json/index.php)

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

[examples/path/index.php](examples/path/index.php)

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

[examples/random/index.php](examples/random/index.php)

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

[examples/server/index.php](examples/server/index.php)

~~~php
<?php

require "../../vendor/autoload.php";

use Pebble\Server;

$scheme_and_host = (new Server())->getSchemeAndHost();
echo $scheme_and_host;

// Prints something like this
// -> http://localhost:8000


~~~