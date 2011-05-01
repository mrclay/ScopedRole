# ScopedRole
Scoped Role is a PHP5.3 library for managing a [contextual role-based capability control system](http://www.mrclay.org/2011/04/01/designing-a-contextual-role-based-capability-control-system/) with a SQL backend. Currently `PDO` and `Zend_Db`-based backends are available, but there are interfaces available if you want to write your own.

This project aims to be generic enough to be dropped into any PHP framework. You provide the user IDs, capabilities and roles, and ScopedRole gives you a simple API to manage them.

## Requirements
PHP 5.3.1
Any database supported by PDO/Zend_Db
An autoloader implementation for loading Zend/NotORM class files

## Status
All tests are passing, but still alpha code. Name/API changes might be around the corner.

## API
Basically you create a "Storage" container wrapping a `PDO` or `Zend_Db_Adapter_Abstract`. From this you can spawn a lightweight `UserContext` value object (e.g. to store in a session) or `Context` object for real-time querying. To improve performance on most requests, the storage implementations have all their modification methods in a separate `Editor` class. By relying on cached `UserContext` objects, most requests only require one small autoloaded class.

## TODO
More documentation, examples, etc. Implementation into a working system to see if the API needs more work.