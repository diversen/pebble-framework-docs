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

```examples/migration/up.php ->```

~~~php
<?php

include_once "vendor/autoload.php";

use Pebble\Service\MigrationService;
use Pebble\App\AppBase;

// Get migration instance using service class
$migrate = (new MigrationService())->getMigration();

// Or use AppBase class
$migrate = (new AppBase())->getMigration();

// This will migrate both SQL files 0001.sql and 0002.sql
// Unless they already have been migrated
$migrate->up(2);

// This would also migrate both version up
// $migrate->up();
~~~

Run the command from a terminal: 

    php examples/migration/up.php

You `.migration` file will now have version `2`. 

Let's also create a command for running the down migration: 

```examples/migration/down.php ->```

~~~php
<?php

include_once "vendor/autoload.php";

use Pebble\Service\MigrationService;
use Pebble\App\AppBase;

// Get migration instance using service class
$migrate = (new MigrationService())->getMigration();

// Or use AppBase class
$migrate = (new AppBase())->getMigration();

// This will migrate both SQL files 0001.sql and 0002.sql
// Unless they already have been migrated
$migrate->down();

// You could also use
// $migrate->down(0);

// Migrate down to version 1. Drops tables in 0002.sql
// $migrate->down(1);

// Migrate down to version 0 Drops tables in 0001.sql
// $migrate->down(0);
~~~

Migrate down from a terminal:

    php examples/migration/down.php

Your `.migration` file is now removed (version 0)

Migrate up again as we will use the new database schema when using the 
`Pebble\Auth` class.  

<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/600-Migration.md'>Edit this page on GitHub</a>