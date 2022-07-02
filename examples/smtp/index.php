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
