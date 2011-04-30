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
     * @param string $title
     * @return int
     */
    public function createContextType($title)
    {
        return $this->_storage->getEditor()->createContextType($title);
    }

    /**
     * @param string $title
     * @param int $contextTypeId
     * @return int
     */
    public function createContext($title, $contextTypeId = null)
    {
        return $this->_storage->getEditor()->createContext($title, $contextTypeId);
    }

    /**
     * @param string $title
     * @param int $sortOrder
     * @return int
     */
    public function createRole($title, $sortOrder = null)
    {
        return $this->_storage->getEditor()->createRole($title, $sortOrder);
    }

    /**
     * @param string $title
     * @param bool $isSuitableForRole
     * @param int $sortOrder
     * @return int
     */
    public function createCapability($title, $isSuitableForRole = true, $sortOrder = null)
    {
        return $this->_storage->getEditor()->createCapability($title, $isSuitableForRole, $sortOrder);
    }

    public function addCapabilityToRole($capabilityId, $roleId)
    {
        return $this->_storage->getEditor()->addCapability($roleId, $capabilityId);
    }

    public function removeCapabilityFromRole($capabilityId, $roleId)
    {
        return $this->_storage->getEditor()->removeCapability($roleId, $capabilityId);
    }
}

