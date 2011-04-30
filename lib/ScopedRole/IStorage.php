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
    public function hasCapability($contextId, $userId, $capability);

    /**
     * @param int $contextId
     * @param int $userId
     * @return array
     */
    public function fetchRoles($contextId, $userId);

    /**
     * @param int $contextId
     * @param int $userId
     * @return array
     */
    public function fetchCapabilities($contextId, $userId);
}