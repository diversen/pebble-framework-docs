<?php declare (strict_types = 1);

require_once "vendor/autoload.php";

use Diversen\MinimalCli;
use Pebble\CLI\User;
use Pebble\CLI\DB;
use Pebble\CLI\Migrate;
use Pebble\CLI\Translate;

use App\CliTestCommand;

$cli = new MinimalCli();
$cli->commands = [
    'user' => new User(),
    'db' => new DB(),
    'migrate' => new Migrate(),
    'translate' => new Translate(),
    'filesize' => new CliTestCommand(),
];


$cli->runMain();
