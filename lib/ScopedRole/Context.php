<?php

namespace ScopedRole;

class Context {

    /**
     * @var int
     */
    protected $_id;

    /**
     * @var IStorage
     */
    protected $_storage;

    public function __construct(Core $core, $contextId)
    {
        $this->_storage = $core->getStorage();
        $this->_id = $contextId;
    }

    /**
     * @param int $userId
     * @param string $capabilityKey
     * @return bool
     */
    public function hasCapability($userId, $capabilityKey)
    {
        return $this->_storage->hasCapability($this->_id, $userId, $capabilityKey);
    }

    /**
     * @param int $roleId
     * @param int $userId
     * @return int|false
     */
    public function grantRole($roleId, $userId)
    {
        return $this->_storage->getEditor()->grantRole($roleId, $userId, $this->_id);
    }

    /**
     * @param int $roleId
     * @param int $userId
     * @return bool
     */
    public function revokeRole($roleId, $userId)
    {
        return $this->_storage->getEditor()->revokeRole($roleId, $userId, $this->_id);
    }

    /**
     * @param int $capabilityId
     * @param int $userId
     * @return int|false
     */
    public function grantCapability($capabilityId, $userId)
    {
        return $this->_storage->getEditor()->grantCapability($capabilityId, $userId, $this->_id);
    }

    /**
     * @param int $capabilityId
     * @param int $userId
     * @return bool
     */
    public function revokeCapability($capabilityId, $userId)
    {
        return $this->_storage->getEditor()->revokeCapability($capabilityId, $userId, $this->_id);
    }

    public function fetchRoles($userId)
    {
        return $this->_storage->fetchRoles($this->_id, $userId);
    }

    public function fetchCapabilities($userId)
    {
        return $this->_storage->fetchCapabilities($this->_id, $userId);
    }
}