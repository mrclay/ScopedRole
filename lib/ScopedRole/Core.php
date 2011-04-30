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
     * @param string $context
     * @param int $userId
     * @param string $capability
     * @return bool
     */
    public function hasCapability($context, $userId, $capability)
    {
        $contextId = $this->_storage->fetchId('context', $context);
        if ($contextId === false) {
            return false;
        }
        return $this->_storage->hasCapability($contextId, $userId, $capability);
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
