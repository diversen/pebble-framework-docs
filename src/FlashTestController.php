<?php

namespace App;

use Pebble\Flash;
use Pebble\Attributes\Route;

class FlashTestController
{
    private $flash;
    function __construct()
    {
        $this->flash = new Flash();
    }

    #[Route('/')]
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

    #[Route('/click')]
    public function click(array $params, Object $object)
    {
        $random = rand(0, 10);
        $message = "Your clicked a link and got this random number: $random";
        $this->flash->setMessage($message, 'info', ['flash_remove' => true]);
        header("Location: /");
    }
}
