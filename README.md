# About 

This is the documentation for the [pebble-framework](https://github.com/diversen/pebble-framework)

You may read it on the [pebble-framework-docs site](https://diversen.github.io/pebble-framework-docs/)

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

Install simple-file-watch (for adding changed php files to mkdocs):

    npm install -g simple-file-watch

You will also need a working version of php and composer. Install
requirements: 

    composer install

## Edit and watch

Watch while editing source files [src-docs](src-docs):

    source venv/bin/activate
    ./bin/serve.sh

Live reload at [http:/localhost:9000](http://localhost:9000)


## Build docs

This builds a static HTML site in `site`.

    source venv/bin/activate
    ./bin/build.sh

## Commit to docs/

Shorthand command:

    ./bin/commit.sh
