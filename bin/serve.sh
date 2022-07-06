#!/bin/sh
mkdocs serve -a 127.0.0.1:9000 &
simple-file-watch --extension='md,php' --path='examples' --path='src-docs' --path='src' --command='./bin/generate-mkdocs.php' &
