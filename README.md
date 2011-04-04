# ScopedRole
Scoped Role is a PHP5.3 class library for managing a [contextual role-based capability control system](http://www.mrclay.org/2011/04/01/designing-a-contextual-role-based-capability-control-system/) with a SQL backend. Currently a `Zend_Db`-based backend is provided, but you can write your own storage adapter that implements the `ScopedRole\IStorage` interface if you like.

The API is minimal, the code is alpha. You've been warned.

## API
The API is still coalescing, but basically you'll inject a `Zend_Db_Adapter` and an optional table prefix into a storage container, then inject this into a `Core` object. Using that you can spawn a `Core_Editor` object, which contains the front-end API for managing contexts, roles, and capabilities. Or you can create a lighter-weight object to do more common queries with a key-value cache speeding up things.