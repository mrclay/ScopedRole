<?php

require_once __DIR__ . '/init.php';

header('Content-Type: text/plain;charset=utf-8');

scrl_test_all(new ScopedRole\Storage_NotORM(scrl_get_PDO()));

scrl_test_all(new ScopedRole\Storage_ZendDb(scrl_get_ZendDb()));

function scrl_test_all(ScopedRole\IStorage $storage) {
    scrl_rebuild_tables();

    echo "\nTesting " . get_class($storage) . "\n\n";

    $editor = $storage->getEditor();

    // create capabilities & related roles

    $canReadId = $editor->createCapability('can_read');
    $canWriteId = $editor->createCapability('can_write');

    $readerId = $editor->createRole('reader');
    $editor->addCapability($readerId, $canReadId);

    $writerId = $editor->createRole('writer');
    $editor->addCapability($writerId, $canReadId);
    $editor->addCapability($writerId, $canWriteId);

    // create context, grant roles to users

    $john = 1;
    $jane = 2;

    $contextTypeId = $editor->createContextType();
    $context = $editor->createContext('default', $contextTypeId);
    $context->grantRole($readerId, $john);
    $context->grantRole($writerId, $jane);
    $context->grantRole($readerId, $jane);

    assertTrue(  $context->hasCapability($john, 'can_read' ), 'Context: John can read');
    assertTrue(  $context->hasCapability($jane, 'can_read' ), 'Context: Jane can read');
    assertTrue(! $context->hasCapability($john, 'can_write'), 'Context: John can\'t write');
    assertTrue(  $context->hasCapability($jane, 'can_write'), 'Context: Jane can write');

    $janeCapabilities = $context->fetchCapabilities($jane);
    $johnCapabilities = $context->fetchCapabilities($john);
    $janeRoles = $context->fetchRoles($jane);

    assertTrue($janeCapabilities == array (
          1 => 'can_read',
          2 => 'can_write',
        ), "Context: Jane has right capabilities");
    assertTrue($johnCapabilities == array (
          1 => 'can_read',
        ), "Context: John has right capabilities");
    assertTrue($janeRoles == array (
          2 => 'writer',
          1 => 'reader',
        ), "Context: Jane has right roles");

    // revoke Jane's writer role

    $context->revokeRole($writerId, $jane);

    assertTrue(! $context->hasCapability($jane, 'can_write'), "Context: revoked Jane's reader role");

    // grant John the capability directly

    $context->grantCapability($canWriteId, $john);

    assertTrue(count($context->fetchCapabilities($john)) === 2, "Context: John can write...");
    assertTrue(count($context->fetchRoles($john)) === 1, "Context: even though he's only a reader.");

    // UserContext value object

    $uc = $storage->fetchUserContext($john);

    assertTrue($uc->hasCapability('can_write'), "UserContext: hasCapability");
    assertTrue(! $uc->hasRole('writer'), "UserContext: hasRole");
    assertTrue($uc->isFresh(), "UserContext: isFresh 1");
    assertTrue(! $uc->isFresh(100, time() + 150), "UserContext: isFresh 2");

    $context->grantRole($writerId, $john);

    assertTrue(! $uc->hasRole("writer"), "UserContext: stale data");

    $uc = $uc->getRefreshed($storage);

    assertTrue($uc->hasRole("writer"), "UserContext: getRefreshed");
}