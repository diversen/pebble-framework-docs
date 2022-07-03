## SMTP

The `Pebble\SMTP` class uses the following two packages `phpmailer/phpmailer` and `erusev/parsedown` 

In order to use the SMTP mail system you will have to require the following packages: 

    composer require erusev/parsedown
    composer require phpmailer/phpmailer

This is an example of the configuration used for the SMTP instance: 

(config/SMTP.php) -&gt;

~~~php
<?php

// Configuration for PHPMailer
return [
    'DefaultFrom' => 'mail@10kilobyte.com',
    'DefaultFromName' => 'Time Manager',
    'Host' => 'smtp-relay.sendinblue.com',
    'Port' => 587,
    'SMTPAuth' => true,
    'SMTPSecure' => 'tls',
    'Username' => 'username',
    'Password' => 'password',
    'SMTPDebug' => 0
];

~~~

Now you can send some HTML or Markdown emails: 

(examples/smtp/index.php) -&gt;

~~~php
<?php

require '../../vendor/autoload.php';

use Pebble\SMTP;
use Pebble\Service\ConfigService;

// Get SMTP config array
$config = (new ConfigService())->getConfig();
$smtp_settings = $config->getSection('SMTP');

// Get SMTP instance
$smtp = new SMTP($smtp_settings);

// Some attachements to attach
$paths_to_attachments = [];

// Send text and HTML
$smtp->send(
    'to@mail.com',
    'test subject',
    'Mail content in text',
    '<p>Mail content in HTML</p>',
    $paths_to_attachments
);

// Safe mode on markdown (defaults to true)
$smtp->setSafeMode(true);

// Send markdown. The text content of the email is the raw markdown
$smtp->sendMarkdown(
    'to@mail.com',
    'test subject',
    '### Test markdown',
    $paths_to_attachments = []
);

// Specify both the text and the markdown content
$smtp->sendTextMarkdown(
    'to@mail.com',
    'test subject',
    'Text content',
    '### Markdown content',
    $paths_to_attachments = []
);

// Alter from email
$stmp->setFrom('another+from@mail.com');
$smtp->setFromName('Mr Doe');

~~~




<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/920-SMTP.md'>Edit this page on GitHub</a>