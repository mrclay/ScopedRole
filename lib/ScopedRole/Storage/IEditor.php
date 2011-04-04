<?php

namespace ScopedRole;

interface Storage_IEditor {
    /**
     * @param string $key
     * @return int
     */
    public function createContextType($key);

    /**
     * @param string $key
     * @param int $typeId
     * @return int
     */
    public function createContext($key, $contextTypeId);

    /**
     * @param string $key
     * @param int $sortOrder
     * @return int
     */
    public function createRole($key, $sortOrder);

    /**
     * @param string $key
     * @param bool $isSuitableForRole
     * @param int $sortOrder
     * @return int
     */
    public function createCapability($key, $isSuitableForRole, $sortOrder);

    /**
     * @param int $roleId
     * @param int $capabilityId
     * @return int
     */
    public function addCapability($roleId, $capabilityId);

    /**
     * @param int $roleId
     * @param int $capabilityId
     * @return bool
     */
    public function removeCapability($roleId, $capabilityId);

    /**
     * @param int $roleId
     * @param int $userId
     * @param int $contextId
     * @return int
     */
    public function grantRole($roleId, $userId, $contextId);

    /**
     * @param int $roleId
     * @param int $userId
     * @param int $contextId
     * @return bool
     */
    public function revokeRole($roleId, $userId, $contextId);

    /**
     * @param int $capabilityId
     * @param int $userId
     * @param int $contextId
     * @return int|false
     */
    public function grantCapability($capabilityId, $userId, $contextId);

    /**
     * @param int $capabilityId
     * @param int $userId
     * @param int $contextId
     * @return bool
     */
    public function revokeCapability($capabilityId, $userId, $contextId);
}