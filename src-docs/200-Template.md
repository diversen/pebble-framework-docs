The `Pebble\Template` class is used for creating secure HTML templates. It uses the
`Pebble\Special` class for encoding the template variables. 

Let's create a main page template with the variables `$title` and `$content` in the `src/templates` dir.  
This is the dir where all templates are placed for this project. 

<!-- include: src/templates/main.php -->

We will also create a page template for showing content. We create some paragraphs
and then we loop over each one of them.

<!-- include: src/templates/page.php -->

We add a new controller class called ` TemplateTest` in the `src` dir. 

<!-- include: src/TemplateTest.php -->

Now we can tie it all together in our `index.php` file

<!-- include: examples/template/index.php -->

Run the application:

    php -S localhost:8000 -t examples/template

Visit a route that does not exist and you will get an error, e.g: http://localhost:8000/does/not/exist

Or visit a route that exists: http://localhost:8000/user/Helena
