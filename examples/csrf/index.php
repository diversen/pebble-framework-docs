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
