<?php

ini_set('display_errors', 1);
require __DIR__ . '/test-resources.php';
scrl_setup_autoloading();
$pdo = scrl_get_PDO();


$queries = explode('/* separator */', file_get_contents(__DIR__ . '/../sql/tables-drop.sql'));

foreach ($queries as $sql) {
    $pdo->exec($sql);
}

$queries = explode('/* separator */', file_get_contents(__DIR__ . '/../sql/tables-create.sql'));

foreach ($queries as $sql) {
    $pdo->exec($sql);
}
