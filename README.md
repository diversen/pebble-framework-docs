# About 

This is the documentation for the [pebble-framework](https://github.com/diversen/pebble-framework)

# Build

This documentation is built using [mkdocs](https://www.mkdocs.org/)

## Install

    git clone git@github.com:diversen/pebble-framework-docs.git
    cd pebble-framework-docs.git

Create python virtual environment:

    virtualenv venv

Activate virtualenv:

    source venv/bin/activate

Install mkdocs:

    pip install -r requirements.txt

## Watch

Live reload at [http:/localhost:9000](http://localhost:9000)

Watch changes in `docs`:

    mkdocs serve -a 127.0.0.1:9000

Watch changes in `examples/`, `src`, and `src-docs/`:

Install `simple-file-watch`

    npm install -g simple-file-watch
    simple-file-watch --extension='md,php' --path='examples' --path='src-docs' --path='src' --command='./bin/generate-mkdocs.php'

## Watch shortcut

If the mkdocs and simple-file-watch are installed, you may run: 

    source venv/bin/activate
    ./bin/serve.sh

## Edit src files

Edit files in [src-docs](src-docs)

## Build mkdocs

This builds a static HTML site in `site`. 

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
