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
