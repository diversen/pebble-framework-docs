Requirements

    PHP >= 7.4.3

### As a dependency in a project:

    composer require diversen/pebble-framework

### Documentation examples

You may clone the **pebble-framework-docs**, in order to
easily run and edit all code examples in this documentation: 

    git clone https://github.com/diversen/pebble-framework-docs.git 
    cd pebble-framework-docs
    composer install

### MySQL 

The documentation uses MySQL. If you want to run all code examples
you will need access to a MySQL database. 

Install (run) a MySQL image that will work:

    docker run -p 3306:3306 --name mysql-server -e MYSQL_ROOT_PASSWORD=password -d mysql:5.7

Create a database:

    ./cli.sh db --server-connect
    CREATE DATABASE `pebble`;

If you can not connect, then edit the database configuration file:

<!-- include: config-locale/DB.php -->

### Other docker commands

List containers 

    docker container ls

Stop container (mysql-server):

    docker stop mysql-server

Start container (mysql-server) again:

    docker start mysql-server

Remove container (you will need to run the 'run' command again):

    docker rm mysql-server
