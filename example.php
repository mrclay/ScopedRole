<?php
// example usage of the API (early)


use ScopedRole\Core;
use ScopedRole\Storage_ZendDb;

// setup Core
$db = new Zend_Db_Adapter_Mysqli($config);
$storage = new Storage_ZendDb($db);
$core = new Core($storage);

// most requests use light API for queries
// e.g. you know context key and userId
$contextId = $storage->fetchId('contexts', $contextKey);
// this UserContext obj pre-fetches roles/capabilities (and in future versions will
// use a KV cache to speed this up)
$userContext = new ScopedRole\UserContext($core, $contextId, $userId);
if ($userContext->hasCapability('can edit forum')) { // a quick in_array check
    // allow user to edit forum
}

// heavier API reserved for when you need to make edits
// not really happy with this API, but it works
$editor = $core->getEditor();
// create some things
$contextTypeId = $editor->createContextType('course');
$contextId = $editor->createContext('course 123', $contextTypeId);
$studentRoleId = $editor->createRole('student');
$teacherRoleId = $editor->createRole('teacher');
$viewCapabilityId = $editor->createCapability('can view course');
$editCapabilityId = $editor->createCapability('can edit course');
// link capabilities and roles
$editor->addCapabilityToRole($viewCapabilityId, $studentRoleId);
$editor->addCapabilityToRole($viewCapabilityId, $teacherRoleId);
$editor->addCapabilityToRole($editCapabilityId, $teacherRoleId);
// assignments within a context
$context = new ScopedRole\Context($core, $contextId);
$context->grantRole($studentRoleId, $userId);
// give a user an extra capability outside of roles

$context->grantCapability($editor->createCapability('can eat cake'), $userId);
