<?php

namespace App;

use Pebble\Template;
use Pebble\Attributes\Route;
use Pebble\Router\Request;

class TemplateTest {

    #[Route(path: '/user/:username')]
    public function userGreeting(Request $request) {
        
        $variables['title'] = 'Greeting with paragraphs'; 
        $variables['username'] = $request->param('username');
        $variables['paragraphs'] = [
            'Hi <w><o><o> ' . $request->param('username') . '<o><o><h> !', 
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
