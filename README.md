# About 

This is the documentation for the [pebble-framework](https://github.com/diversen/pebble-framework)

The framework aims at being as simple as possible but no simpler than that. 

# Build

This documentation is built using [mkdocs](https://www.mkdocs.org/)

## Install: mkdocs

    git clone git@github.com:diversen/pebble-framework-docs.git
    cd pebble-framework-docs.git

Create python virtual environment:

    virtualenv venv

Activate virtualenv:

    source venv/bin/activate

Install mkdocs:

    pip install mkdocs

## Watch

Live reload at [http:/localhost:9000](http://localhost:9000)

    mkdocs serve -a 127.0.0.1:9000

Watch changes in markdown files. PHP files are included in the markdown docs. 

Install `simple-file-watch`

    npm install -g simple-file-watch
    simple-file-watch --extension='md' --path='src-docs' --command='./bin/generate-mkdocs.php'

## Edit src files

Edit files in [src-docs](src-docs)

## Build mkdocs

This builds static HTML site in `site`. 

    mkdocs build

## Build single README.md

Build all documentation as a single [README-docs.md](README-docs.md) file.
This is not using mkdocs

    ./bin/generate-docs-single-file.php

## Commit to docs/

Shorthand command:

This will update markdown files, insert php code in .md files, build mkdocs,
copy to pebble-framework/docs, and commit with the message 'docs' 

    ./bin/commit.sh



