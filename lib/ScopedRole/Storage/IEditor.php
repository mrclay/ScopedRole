<?php

namespace ScopedRole;

interface Storage_IEditor {
    /**
     * @param string $title
     * @return int
     */
    public function createContextType($title);

    /**
     * @param string $title
     * @param int $typeId
     * @return int
     */
    public function createContext($title, $contextTypeId);

    /**
     * @param string $title
     * @param int $sortOrder
     * @return int
     */
    public function createRole($title, $sortOrder);

    /**
     * @param string $title
     * @param bool $isSuitableForRole
     * @param int $sortOrder
     * @return int
     */
    public function createCapability($title, $isSuitableForRole, $sortOrder);

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