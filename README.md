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

## Run mkdocs

Live reload at [http:/localhost:9000](http://localhost:9000)

    mkdocs serve -a 127.0.0.1:9000

## Watch

Watch changes in markdown files. PHP files are included in the markdown docs. 
Install `simple-file-watch`

    npm install -g simple-file-watch

## Watch md-files

    simple-file-watch --extension='md' --path='src-docs' --command='./bin/generate-mkdocs.php'

## Edit src files

Just edit files in [src-docs](src-docs)

## Build mkdocs

This builds static HTML site in `site`. 

    mkdocs build

## Publish docs

    cp -rf site/* ../pebble-framework/docs/

## Build single README.md

Build all documentation as a single [README-docs.md](README-docs.md) file

    ./bin/generate-docs-single-file.php



