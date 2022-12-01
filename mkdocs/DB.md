The `Pebble\DB` class should work with any database, but in this documentation we will stick with a MySQL database. 
It is also the only database that has a `schema` containing tables for `Pebble\Auth`, `Pebble\ACL`, and `Pebble\ACLRole`. 
For instruction on running MySQL you may refer to [Setup MySQL](index#docker-mysql) 

### Usage

Now we can test all methods. Most of these methods should be self-explanatory. 

```examples/database/index.php ->```

~~~php
<?php

require_once "../../vendor/autoload.php";

use Pebble\App\AppBase;
use Pebble\Service\DBService;
use Pebble\ExceptionTrace;

$app_base = new AppBase();
$app_base->setErrorHandler();

function debug($message) {
    echo "$message<br/>";
}

try {

    $db = (new DBService())->getDB();
    debug("getDb");

    $db->prepareExecute('DROP TABLE IF EXISTS note');
    debug("prepareExecute. Dropped note table");

    $table = <<<EOF
    CREATE TABLE `note` (
        `id` int NOT NULL AUTO_INCREMENT,
        `entry` text COLLATE utf8mb4_general_ci,
        `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        `auth_id` int NOT NULL,
        `public` tinyint(1) DEFAULT '0',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    EOF;  

    $db->prepareExecute($table);
    debug("prepareExecute. Created note table");

    $db->beginTransaction();
    $db->insert('note', ['entry' => 'This is a entry text by user id 1', 'auth_id' => 1]);
    $db->insert('note', ['entry' => 'This is a entry text by user id 2', 'auth_id' => 2]);
    $db->insert('note', ['entry' => 'This is another entry text by user id 1', 'auth_id' => 1]);
    $last_insert_id = $db->lastInsertId();
    $db->commit();
    debug("commit. Commited 3 rows");
    debug("lastInsertId. Last insert ID: $last_insert_id");

    // inTransactionExecute() is a shortcut for beginTransaction() and prepareExecute() and commit()
    // It throws an exception if the transaction fails.
    $last_insert_id = $db->inTransactionExec(function () use ($db) {
        $db->insert('note', ['entry' => 'Another entry by user id 1', 'auth_id' => 1]);
        $db->insert('note', ['entry' => 'Another entry by user id 2', 'auth_id' => 2]);
        $last_insert_id = $db->lastInsertId();
        return $last_insert_id;
    });

    debug("lastInsertId. Last insert ID: $last_insert_id");
    
    $num_rows = $db->getTableNumRows('note', 'id');
    debug("getTableNumRows. $num_rows rows in table (counting 'id')");

    $row = $db->getOne('note', ['auth_id' => '1']);
    debug("getOne. Got row with ID '$row[id]' and entry '$row[entry]'");
    
    $rows = $db->getAll('note', ['auth_id' => 1]);
    foreach($rows as $row) {
        debug("getAll. Got row with ID '$row[id]' and entry '$row[entry]'");
    }

    // This gives the same rows as above, but with option for 'order' and 'limit'
    $rows = $db->getAllQuery('SELECT * FROM note', ['auth_id' => '1'], ['id' => 'ASC'], [0, 10]);
    foreach($rows as $row) {
        debug("getAllQuery. Got row with ID '$row[id]' and entry '$row[entry]'");
    }

    $db->update('note', ['entry' => 'This is the UPDATED entry text by user id 1'], ['id' => 1]);
    debug("updated. Updated row with ID '$row[id]'");
    $row = $db->getOneQuery('SELECT * FROM note', ['auth_id' => 1], ['updated' => 'DESC']);
    debug("getOneQuery. Got updated row with ID '$row[id]' and updated entry '$row[entry]'");

    // Use any SQL for getting a single row. Positional parameters
    $row = $db->prepareFetch("SELECT * FROM note WHERE id = ?", [1]);
    debug("prepareFetch (positional parameters). Got row with ID '$row[id]' and entry '$row[entry]'");

    // Use any SQL for getting multiples row. Positional parameters
    $rows = $db->prepareFetchAll("SELECT * FROM note WHERE id >= ?", [1]);
    foreach($rows as $row) {
        debug("prepareFetchAll (positional parameters). Got row with ID '$row[id]' and entry '$row[entry]'");
    }

    // Use any SQL for getting multiples row. Named parameters
    $rows = $db->prepareFetchAll("SELECT * FROM note WHERE id >= :id", ['id' => 1]);
    foreach($rows as $row) {
        debug("prepareFetchAll (named parameters). Got row with ID '$row[id]' and entry '$row[entry]'");
    }

    $db->delete('note', ['id' => 1] );
    debug("Deleted row with id = 1");

    $affected_rows = $db->rowCount();
    debug("rowCount (affected rows): $affected_rows");

    // Get dbh database handle
    $db->getDbh();
    
    // Get LIMIT SQL string
    $limit = $db->getLimitSql([0, 10]);
    debug("getLimitSql. $limit");

    // Get order SQL
    $order = $db->getOrderBySql(['id' => 'ASC', 'username' => 'DESC']);
    debug("getOrderBySql. $order");

    // Get where SQL
    $where_sql = $db->getWhereSql(['auth_id ' => 7, 'birthday' => '1972-02-25']);
    debug("getOrderBySql. $where_sql");

} catch (Exception $e) {
    debug("Error: " . $e->getMessage());
    echo "<pre>" . ExceptionTrace::get($e) . "</pre>";

}


~~~

You may run the example:

    php -S localhost:8000 -t examples/database

And then visit [http://localhost:8000/](http://localhost:8000/)





<hr /><a href='https://github.com/diversen/pebble-framework-docs/blob/main/src-docs/500-DB.md'>Edit this page on GitHub</a>