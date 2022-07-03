The Auth instance is created using a `Pebble\DB` object and an array of `cookie settings`. 

This is what the `Auth` cookie configuration, which we will use, looks like:

<!-- include: config/Auth.php -->

And now let's use our newly created Auth object in an example: 

<!-- include: examples/auth/index.php -->

You may run the Auth example:

    php -S localhost:8000 -t examples/auth

And go to [http://localhost:8000](http://localhost:8000)