Migration creates or updates your database schema. 
A `.migration` file will keep track of the current schema version, 
so that the migration system knows what to execute.  

The `up` migrations are placed in `migrations/up/`.

Let's load some SQL into the database. The database statements in `migrations/up/0001.sql` 
will make it possible to create users and to check users against an ACL system. 
The `0002.sql` creates a `note` table.

There is a corresponding `migrations/up/down` folder for migrating down. This folder
holds all the statements that will *undo* the up migrations. 

Let's create a command for running the up migration: 

<!-- include: examples/migration/up.php -->

Run the command from a terminal: 

    php examples/migration/up.php

You `.migration` file will now have version `2`. 

Let's also create a command for running the down migration: 

<!-- include: examples/migration/down.php -->

Migrate down from a terminal:

    php examples/migration/down.php

Your `.migration` file is now removed (version 0)

Migrate up again as we will use the new database schema when using the 
`Pebble\Auth` class.  