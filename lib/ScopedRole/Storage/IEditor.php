<?php

namespace ScopedRole;

interface Storage_IEditor {
    /**
     * @param string $title
     * @return int
     */
    public function createContextType($title = 'default');

    /**
     * @param string $title
     * @param int $typeId
     * @return Context
     */
    public function createContext($title = 'default', $contextTypeId = null);

    /**
     * @param string $title
     * @param int $sortOrder
     * @return int
     */
    public function createRole($title, $sortOrder = null);

    /**
     * @param string $title
     * @param bool $isSuitableForRole
     * @param int $sortOrder
     * @return int
     */
    public function createCapability($title, $isSuitableForRole = true, $sortOrder = null);

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

    /**
     * @param string $title
     * @return Context|null
     */
    public function getContextByTitle($title = 'default');
}