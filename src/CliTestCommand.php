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
