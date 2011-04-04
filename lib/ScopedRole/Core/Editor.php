<?php

namespace ScopedRole;

class Core_Editor {

    /**
     * @var IStorage
     */
    protected $_storage;

    public function __construct(Core $core)
    {
        $this->_storage = $core->getStorage();
    }

    /**
     * @param string $key
     * @return int
     */
    public function createContextType($key)
    {
        return $this->_storage->getEditor()->createContextType($key);
    }

    /**
     * @param string $key
     * @param int $typeId
     * @return int
     */
    public function createContext($key, $contextTypeId)
    {
        return $this->_storage->getEditor()->createContext($key, $contextTypeId);
    }

    /**
     * @param string $key
     * @param int $sortOrder
     * @return int
     */
    public function createRole($key, $sortOrder = null)
    {
        return $this->_storage->getEditor()->createRole($key, $sortOrder);
    }

    /**
     * @param string $key
     * @param bool $isSuitableForRole
     * @param int $sortOrder
     * @return int
     */
    public function createCapability($key, $isSuitableForRole = true, $sortOrder = null)
    {
        return $this->_storage->getEditor()->createCapability($key, $isSuitableForRole, $sortOrder);
    }
}

