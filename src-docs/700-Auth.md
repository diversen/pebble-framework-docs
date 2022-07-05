The `Pebble\Auth` class is used to authenticate users using the database table `auth`. The Auth instance is created using a `Pebble\DB` object and an array of `cookie settings`. 

The cookie settings could look something like this:

<!-- include: config/Auth.php -->

The following example shows a test of all methods:

<!-- include: examples/auth/index.php -->

You may run this example:

    php -S localhost:8000 -t examples/auth

And go to [http://localhost:8000](http://localhost:8000)