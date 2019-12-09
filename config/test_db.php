<?php
$db = require __DIR__ . '/db.php';
// test database! Important not to run tests on production or development databases
$db['dsn'] = 'mysql:host=db;dbname=u715364900_chivocuate';

return $db;
