The `Pebble\SMTP` class uses the following two packages `phpmailer/phpmailer` and `erusev/parsedown` 

In order to use the SMTP mail system you will have to require the following packages: 

    composer require erusev/parsedown
    composer require phpmailer/phpmailer

This is an example of the configuration used for the SMTP instance: 

<!-- include: config/SMTP.php -->

Now you can send some HTML or Markdown emails: 

<!-- include: examples/smtp/index.php -->


