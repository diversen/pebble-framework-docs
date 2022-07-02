Flash messages are messages that are displayed to the client -
usually after some action has been performed. 

If the client has logged in, then it is normal to display 
a message like *Welcome. You are now logged in*.

In the following example we make two routes, `/` and `/click`. 

The `/` route just shows a link, that says *Click me*. When 
clicking this link we navigate to `/click` which sets a flash message
in the `$_SESSION['flash']` variable. 

Then we are redirected back to `/`, where the flash messages are 
displayed and cleared.  

<!-- include: src/FlashTestController.php -->

We execute this controller in our `index.php` file: 

<!-- include: examples/flash/index.php -->

Run the example: 

    php -S localhost:8000 -t examples/flash

You may then visit http://localhost:8000

