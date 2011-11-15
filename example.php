<?php
/*
 * Example usage of the ScopedRole API
 *
 * @todo finish this!
 */

// setup your autoloading...

// set up storage. also available in Zend_Db
$storage = new ScopedRole\Storage_NotORM(new PDO("..."));

// Sample function to get a UserContext object and propogate it in $_SESSION
function userCode_getUserContext(ScopedRole\IStorage $storage, $userId, $contextId) {
    $sessKey = 'scrl_userContext' . $contextId;
    if (isset($_SESSION[$sessKey]) && $_SESSION[$sessKey] instanceof ScopedRole\UserContext) {
        $uc = $_SESSION[$sessKey]; /* @var $uc ScopedRole\UserContext */
        if (! $uc->isFresh($ttl)) {
            $uc = $uc->getRefreshed($storage);
            $_SESSION[$sessKey] = $uc;
        }
    } else {
        $_SESSION[$sessKey] = $storage->fetchUserContext($userId, $contextId);
    }
    return $_SESSION[$sessKey];
}

// fetching/querying the UserContext object
$userId = 123;
$contextId = 1;
$userContext = userCode_getUserContext($storage, $userId, $contextId);
$userContext->hasRole('manager'); // bool
$userContext->hasCapability('breakThings'); // bool


// ...