The pebble frameworks uses the `diversen/minimal-cli-framework` for making command line programs. 

    composer require diversen/minimal-cli-framework

### Example sub-command

Create a command: 

```src/CliTestCommand.php ->```

~~~php
<?php

declare(strict_types=1);

namespace App;

use Diversen\ParseArgv;

class CliTestCommand {

    /**
     * Command definition
     */
    public function getCommand() {
        return 
            array (
                // Command help
                'usage' => 'A simple test command. Gives file size in bytes',
                
                // Options to the command
                'options' =>    ['--pretty' => 'Pretty format of bytes. In KB, MB, GB, TB'],

                // Arguments to the command
                'arguments' =>  ['file' => 'File to get size of']
            );
    }

    /**
     * Method for getting size in KB, MB, GB, or TB
     */
    private function size2Byte($size) {
        $units = array('KB', 'MB', 'GB', 'TB');
        $currUnit = '';
        while (count($units) > 0  &&  $size > 1024) {
            $currUnit = array_shift($units);
            $size /= 1024;
        }
        return ($size | 0) . $currUnit;
    }

    /**
     * Run the command
     * @param ParseArgv $args
     */
    public function runCommand(ParseArgv $args) {

        // Check if any arguments
        $file = $args->getArgument(0);
        if (!$file) {
            echo "Specify file" . "\n";
            return 1;
        }

        // Check if argument is a file
        if (!file_exists($file) || !is_file($file) ) {
            echo "File does not exist\n";
            return 1;
        }

        // 
        $size = filesize($file);

        // Check if filesize should be converted
        if ($args->getOption('pretty')) {
            $size = $this->size2Byte($size);
        } else {
            $size.= " Bytes";
        }

        // Print the size of the file
        $res_string = "Size of file: $file is: ";
        echo $res_string . $size . "\n";
        return 0;
    }
}

~~~

### Create CLI program

There is few built-in commands that you can use right away. 

Let's add the  above command to a CLI program with some commands that are included
with the `pebble-framework`. 

```examples/cli/index.php ->```

~~~php
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

~~~

Run the command, e.g: 

    php examples/cli/index.php filesize --pretty ./README.md

Same as:

    php examples/cli/index.php filesize --pr ./README.md

Get help about the command: 

    php examples/cli/index.php filesize -h

Use the built-in DB command (connect to the database):

    php examples/cli/index.php db --connect




<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/922-CLI.md'>Edit this page on GitHub</a>