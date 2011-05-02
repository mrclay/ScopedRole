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
    $canManageId = $editor->createCapability('can_manage');
    $canStoptimeId = $editor->createCapability('can_stop_time');

    $readerRoleId = $editor->createRole('reader');
    $editor->addCapability($readerRoleId, $canReadId);

    $writerRoleId = $editor->createRole('writer');
    $editor->addCapability($writerRoleId, $canReadId);
    $editor->addCapability($writerRoleId, $canWriteId);

    $managerRoleId = $editor->createRole('manager');
    $editor->addCapability($managerRoleId, $canManageId);

    // check role capabilities
    assertTrue($storage->fetchRoleCapabilities($writerRoleId) == array(
            1 => 'can_read',
            2 => 'can_write',
        ), "Storage: fetchRoleCapabilities");

    // create context, grant roles to users

    $john = 1;
    $jane = 2;

    $contextTypeId = $editor->createContextType();
    $context = $editor->createContext('default', $contextTypeId);
    $context->grantRole($readerRoleId, $john);
    $context->grantRole($writerRoleId, $jane);
    $context->grantRole($readerRoleId, $jane);

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

    $context->revokeRole($writerRoleId, $jane);

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

    $context->grantRole($writerRoleId, $john);

    assertTrue(! $uc->hasRole("writer"), "UserContext: stale data");

    $uc = $uc->getRefreshed($storage);

    assertTrue($uc->hasRole("writer"), "UserContext: getRefreshed");

    // try giving John a runtime roles and capabilites

    $uc = $storage->fetchUserContext($john, 1, array('manager', 'superhero'), array('can_stop_time', 'can_lift_cars'));

    assertTrue($uc->getCapabilities() == array (
          2 => 'can_write',
          1 => 'can_read',
          3 => 'can_manage',
          4 => 'can_stop_time',
          'can_lift_cars' => 'can_lift_cars',
        ), "UserContext: runtime roles and capabilities");

    $uc = $uc->getRefreshed($storage);

    assertTrue($uc->getCapabilities() == array (
          2 => 'can_write',
          1 => 'can_read',
          3 => 'can_manage',
          4 => 'can_stop_time',
          'can_lift_cars' => 'can_lift_cars',
        ), "UserContext: runtime properties are persisted");

    assertTrue($uc->getPersistedRoles() == array (
          1 => 'reader',
          2 => 'writer',
        ), "UserContext: getPersistedRoles");

    assertTrue($uc->getPersistedCapabilities() == array (
          2 => 'can_write',
          1 => 'can_read',
        ), "UserContext: getPersistedCapabilities");

    // user with only runtime roles

    $uc = $storage->fetchUserContext(66, 1, array('reader', 'authenticated_user'), array('edit_settings'));

    assertTrue($uc->getCapabilities() == array (
          1 => 'can_read',
          'edit_settings' => 'edit_settings',
        ), "UserContext: runtime roles only");

    $uc = $uc->getRefreshed($storage);

    assertTrue($uc->getPersistedCapabilities() == array(), "UserContext: nothing persisted");
}