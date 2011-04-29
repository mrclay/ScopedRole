<?php

require_once __DIR__ . '/init.php';
require __DIR__ . '/rebuild-tables.php';

$storage = new ScopedRole\Storage_NotORM($pdo);

$core = new ScopedRole\Core($storage);

$userId = 1;

assertTrue($core->hasCapability('default', $userId, 'create-posts') === 0, 'no capability');
