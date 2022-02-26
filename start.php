<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '1024M');

require_once '../config.php';
require_once '../system/library/db/mysqli.php';

require_once '../migrate/Schema.php';
require_once '../migrate/AbstractInsertOnDuplicateKeyUpdate.php';
require_once '../migrate/CustomerInsertOnDuplicateKeyUpdate.php';
require_once '../migrate/OrderInsertOnDuplicateKeyUpdate.php';
require_once '../migrate/ProductUpdate.php';

const DB_DATABASE_OLD = 'old_database';

$classes = [
    \migrate\ProductUpdate::class,
    \migrate\OrderInsertOnDuplicateKeyUpdate::class,
    \migrate\CustomerInsertOnDuplicateKeyUpdate::class,
];

$db = new \DB\MySQLi(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, '');

foreach ($classes as $class) {
    (new $class($db))->apply();
}
