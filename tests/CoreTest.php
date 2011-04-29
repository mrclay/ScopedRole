<?php

require __DIR__ . '/rebuild-tables.php';

$storage = new ScopedRole\Storage_NotORM($pdo);

$core = new ScopedRole\Core($storage);

assertTrue($core->hasCapability('default', 1, 'create-posts') === 0, 'no capability');
