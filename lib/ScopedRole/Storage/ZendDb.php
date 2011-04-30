<?php

namespace ScopedRole;

class Storage_ZendDb implements IStorage {

    /**
     * @param \Zend_Db_Adapter_Abstract $db
     * @param string $prefix
     */
    public function __construct(\Zend_Db_Adapter_Abstract $db, $prefix = 'scrl_')
    {
        $this->_db = $db;
        $this->_prefix = $prefix;
    }

    /**
     * @param string $name
     * @return \Zend_Db_Table
     */
    public function getTable($name)
    {
        static $tables = array();
        if (! isset($tables[$name])) {
            $tables[$name] = new \Zend_Db_Table(array(
                'name' => $name,
                'primary' => 'id',
                'db' => $this->_db,
            ));
        }
        return $tables[$name];
    }

    /**
     * @param string $table
     * @param string $title
     * @return int|false
     */
    public function fetchId($table, $title)
    {
        return $this->_db->fetchOne("
            SELECT id FROM `{$this->_prefix}$table`
            WHERE title = ?
        ", array($title));
    }

    /**
     * @return \Zend_Db_Adapter_Abstract
     */
    public function getDb()
    {
        return $this->_db;
    }

    /**
     * @return Storage_ZendDb_Editor
     */
    public function getEditor()
    {
        static $editor = null;
        if ($editor === null) {
            $editor = new Storage_ZendDb_Editor($this);
        }
        return $editor;
    }

    /**
     * @param int $contextId
     * @param int $userId
     * @return array
     */
    public function fetchRoles($contextId, $userId)
    {
        $userId    = (int)$userId;
        $contextId = (int)$contextId;
        return $this->_db->fetchAssoc("
            SELECT r.id, r.title
            FROM `{$this->_prefix}role` AS r
            JOIN `{$this->_prefix}user_role` AS ur
                ON (r.id = ur.id_role)
            WHERE ur.id_user = $userId AND ur.id_context = $contextId
            ORDER BY r.sortOrder
        ");
    }

    /**
     * @param int $contextId
     * @param int $userId
     * @return array
     */
    public function fetchCapabilities($contextId, $userId)
    {
        $userId    = (int)$userId;
        $contextId = (int)$contextId;
        return $this->_db->fetchAssoc("
                SELECT c.id, c.title
                FROM `{$this->_prefix}user_capability` AS uc
                JOIN `{$this->_prefix}capability` AS c
                    ON (uc.id_capability = c.id)
                WHERE uc.id_context = $contextId
                  AND uc.id_user = $userId
                ORDER BY c.sortOrder
            UNION
                SELECT c.id, c.title
                FROM `{$this->_prefix}user_role` AS ur
                JOIN `{$this->_prefix}role_capability` AS rc
                    ON (ur.id_role = rc.id_role)
                JOIN `{$this->_prefix}capability` AS c
                    ON (rc.id_capability = c.id)
                WHERE ur.id_context = $contextId
                  AND ur.id_user = $userId
                ORDER BY c.sortOrder
        ");
    }

    /**
     * @param int $contextId
     * @param int $userId
     * @param string $capability
     * @return bool
     */
    public function hasCapability($contextId, $userId, $capability)
    {
        $capabilityId = $this->fetchId('capability', $capability);
        if ($capabilityId === false) {
            return false;
        }
        $userId    = (int)$userId;
        $contextId = (int)$contextId;
        $matchingRows = $this->_db->fetchAll("
                SELECT 1
                FROM `{$this->_prefix}user_capability` AS uc
                WHERE uc.id_capability = $capabilityId
                  AND uc.id_context = $contextId
                  AND uc.id_user = $userId
            UNION
                SELECT 1
                FROM `{$this->_prefix}user_role` AS ur
                JOIN `{$this->_prefix}role_capability` AS rc
                    ON (ur.id_role = rc.id_role)
                WHERE rc.id_capability = $capabilityId
                  AND ur.id_context = $contextId
                  AND ur.id_user = $userId
        ");
        return count($matchingRows) > 0;
    }


    /**
     * @var string
     */
    protected $_prefix;

    /**
     * @var \Zend_Db_Adapter_Abstract
     */
    protected $_db;
}