The `Pebble\Template` class is used for creating secure HTML templates. It uses the `Pebble\Special` class for encoding the template variables. Every HTML entity is encoded by default. 

The class has four methods: 

* `Template::getOutput($variables_ary, 'path/to/template.php')`. Return output of a template as a string
* `Template::getOutputRaw($variables_ary, 'path/to/template.php')`. Return output of a template as a string with no encoding
* `Template::render($variables_ary, 'path/to/template.php')`. Output the parsed templates directly to stdout (e.g. the browser).
* `Template::renderRaw($variables_ary, 'path/to/template.php')`.  Output the parsed templates directly to stdout (e.g. the browser) with no encoding.


Let's create a main page template with the variables `$title` and `$content` in the `src/templates` dir. This is the dir where all templates are placed for the examples. 

<!-- include: src/templates/main.php -->

We will also create a page template for showing content. We create some paragraphs and then we loop over each one of them.

<!-- include: src/templates/page.php -->

We add a new controller class called ` TemplateTest` in the `src` dir. 

<!-- include: src/TemplateTest.php -->

Now we can tie it all together in our `index.php` file

<!-- include: examples/template/index.php -->

Run the application:

    php -S localhost:8000 -t examples/template

Visit a route that does not exist and you will get an error, e.g: [http://localhost:8000/does/not/exist](http://localhost:8000/does/not/exist)

Or visit a route that exists: [http://localhost:8000/user/Helena](http://localhost:8000/user/Helena)
