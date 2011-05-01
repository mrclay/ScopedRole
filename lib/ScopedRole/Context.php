<?php

namespace ScopedRole;

/**
 * Class for managing role/capability assignments within a context
 */
class Context {

    /**
     * @var int
     */
    protected $_id;

    /**
     * @var IStorage
     */
    protected $_storage;

    public function __construct(IStorage $storage, $contextId = 1)
    {
        $this->_storage = $storage;
        $this->_id = $contextId;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $userId
     * @param string $capability
     * @return bool
     */
    public function hasCapability($userId, $capability)
    {
        return $this->_storage->hasCapability($userId, $capability, $this->_id);
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

    /**
     * @param int $userId
     * @return array
     */
    public function fetchRoles($userId)
    {
        return $this->_storage->fetchRoles($userId, $this->_id);
    }

    /**
     * @param int $userId
     * @return array
     */
    public function fetchCapabilities($userId)
    {
        return $this->_storage->fetchCapabilities($userId, $this->_id);
    }
}