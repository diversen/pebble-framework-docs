<?php

namespace App;

use ErrorException;

class AppBase
{

    public $base_path = null;

    public function __construct()
    {   
        // Set base path as directory above 'src/'
        $this->base_path = dirname(__DIR__);
    }

    /**
     * Setting include path from AppBase
     */
    public function setIncludePath() {
        // Set above base_path as extra include path
        set_include_path(get_include_path() . PATH_SEPARATOR . $this->base_path);
    }

    /**
     * Set error handler so that any error is an ErrorException
     */
    public function setErrorHandler()
    {
        // Throw on all kind of errors and notices
        set_error_handler(function ($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });
    }
}
