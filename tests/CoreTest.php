<?php

require_once __DIR__ . '/init.php';
require __DIR__ . '/rebuild-tables.php';

/* @var $pdo \PDO */

$storage = new ScopedRole\Storage_NotORM($pdo);

$core = new ScopedRole\Core($storage);

$userId = 1;

$editor = $core->getEditor();

assertTrue($core->hasCapability('default', $userId, 'create-posts') === false, 'no capability');


