The `Pebble\Auth` class is used to authenticate users using the database table `auth`. The Auth instance is created using a `Pebble\DB` object and an array of `cookie settings`. 

The cookie settings could look something like this:

(config/Auth.php) -&gt;

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

The following example shows a test of all methods:

(examples/auth/index.php) -&gt;

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppBase;

$app_base = new AppBase();
$app_base->setErrorHandler();

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

You may run this example:

    php -S localhost:8000 -t examples/auth

And go to [http://localhost:8000](http://localhost:8000)

<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/700-Auth.md'>Edit this page on GitHub</a>