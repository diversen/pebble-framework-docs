## Template

The `Pebble\Template` class is used for creating secure HTML templates. It uses the
`Pebble\Special` class for encoding the template variables. 

Let's create a main page template with the variables `$title` and `$content` in the `src/templates` dir.  
This is the dir where all templates are placed for this project. 

<a href='https://github.com/diversen/pebble-framework-docs/blob/main/src/templates/main.php' target='_blank'>src/templates/main.php</a>

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

<a href='https://github.com/diversen/pebble-framework-docs/blob/main/src/templates/page.php' target='_blank'>src/templates/page.php</a>

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

<a href='https://github.com/diversen/pebble-framework-docs/blob/main/src/TemplateTest.php' target='_blank'>src/TemplateTest.php</a>

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

<a href='https://github.com/diversen/pebble-framework-docs/blob/main/examples/template/index.php' target='_blank'>examples/template/index.php</a>

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
