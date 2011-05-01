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
     * @param int $contextId
     * @param int $userId
     * @return array
     */
    public function fetchRoles($userId, $contextId = 1);

    /**
     * @param int $contextId
     * @param int $userId
     * @return array
     */
    public function fetchCapabilities($userId, $contextId = 1);

    /**
     * @param int $userId
     * @param int $contextId
     * @return UserContext
     */
    public function fetchUserContext($userId, $contextId = 1);
}