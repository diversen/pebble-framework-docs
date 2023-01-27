<?php

require_once "../../vendor/autoload.php";

use Pebble\Service\AuthService;

// Or use AppBase class
$auth = (new AuthService())->getAuth();

// Just for printing what is going on
function debug($message) {
    echo $message . "<br />";    
} 

// Generate a User
$email = 'test@tester.com';
$password = 'strong1234';

$user = $auth->getByWhere(['email' => $email]);

// No user create a user
if (!$user) {
    try {
        $auth->create($email, $password);
        debug("create. User with email $email created");
    } catch (Exception $e) {
        debug($e->getMessage());
    }
}

// Check if verified
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
