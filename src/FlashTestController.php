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
