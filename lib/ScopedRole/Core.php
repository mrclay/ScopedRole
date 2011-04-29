<?php

namespace ScopedRole;

class Core {

    public function __construct(IStorage $storage)
    {
        $this->_storage = $storage;
    }

    /**
     * @return IStorage
     */
    public function getStorage()
    {
        return $this->_storage;
    }

    /**
     * @param string $contextKey
     * @param int $userId
     * @param string $capabilityKey
     * @return bool
     */
    public function hasCapability($contextKey, $userId, $capabilityKey)
    {
        $contextId = $this->_storage->fetchId('context', $contextKey);
        if ($contextId === false) {
            return false;
        }
        return $this->_storage->hasCapability($contextId, $userId, $capabilityKey);
    }

    /**
     * @return Core_Editor
     */
    public function getEditor()
    {
        return new Core_Editor($this);
    }

    /**
     * @var IStorage
     */
    protected $_storage;

    

}
