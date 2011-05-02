<?php

namespace ScopedRole;

interface IStorage {

    /**
     * @return Storage_IEditor
     */
    public function getEditor();

    /**
     * Fetch the id of the primary key in a table
     * @param string $table
     * @param string $title
     * @return int|false
     */
    public function fetchId($table, $title);
    
    /**
     * @param int $contextId
     * @param int $userId
     * @param string $capability
     * @return bool
     */
    public function hasCapability($userId, $capability, $contextId = 1);

    /**
     * @param int $roleId
     * @return array
     */
    public function fetchRoleCapabilities($roleId);

    /**
     * @param int $contextId
     * @param int $userId
     * @return array
     */
    public function fetchUserRoles($userId, $contextId = 1);

    /**
     * @param int $contextId
     * @param int $userId
     * @return array
     */
    public function fetchUserCapabilities($userId, $contextId = 1);

    /**
     * @param int $userId
     * @param int $contextId
     * @param array $runtimeRoles
     * @param array $runtimeCapabilities
     * @return VO_UserContext
     */
    public function fetchUserContext($userId, $contextId = 1, array $runtimeRoles = array(), array $runtimeCapabilities = array());
}