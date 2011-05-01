<?php
/*
 * Example usage of the ScopedRole API
 *
 * @todo finish this!
 */

// setup your autoloading...

// set up sotrage. also available in Zend_Db
$storage = new ScopedRole\Storage_NotORM(new PDO("..."));

$contextId = 1; // the scope in which users have role/capability relationships

// storing UserContext in a session...
$sessKey = 'scrl_userContext';
if (isset($_SESSION[$sessKey]) && $_SESSION[$sessKey] instanceof ScopedRole\UserContext) {
    $uc = $_SESSION[$sessKey]; /* @var $uc ScopedRole\UserContext */
    if (! $uc->isFresh($ttl)) {
        $uc = $uc->getRefreshed($storage);
        $_SESSION[$sessKey] = $uc;
    }
} else {
    $_SESSION[$sessKey] = $storage->fetchUserContext($userId);
}

// using it to get roles

// ...