<?php

require_once __DIR__ . '/init.php';

$queries = explode('/* separator */', file_get_contents(__DIR__ . '/../sql/tables-drop.sql'));

foreach ($queries as $sql) {
    $pdo->exec($sql);
}

$queries = explode('/* separator */', file_get_contents(__DIR__ . '/../sql/tables-create.sql'));

foreach ($queries as $sql) {
    $pdo->exec($sql);
}
