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
                'primary' => Core::getPrimaryKey($name),
                'db' => $this->_db,
            ));
        }
        return $tables[$name];
    }

    /**
     * @param string $table
     * @param string $key
     * @return int|false
     */
    public function fetchId($table, $key)
    {
        $primary = Core::getPrimaryKey($table);
        return $this->_db->fetchOne("
            SELECT $primary FROM `{$this->_prefix}$table`
            WHERE key = ?
        ", array($key));
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
            SELECT r.roleId, r.key
            FROM `{$this->_prefix}roles` AS r
            JOIN `{$this->_prefix}users_roles` AS ur
                ON (r.roleId = ur.roleId)
            WHERE ur.userId = $userId AND ur.contextId = $contextId
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
                SELECT c.capabilityId, c.key
                FROM `{$this->_prefix}users_capabilities` AS uc
                JOIN `{$this->_prefix}capabilities` AS c
                    ON (uc.capabilityId = c.capabilityId)
                WHERE uc.contextId = $contextId
                  AND uc.userId = $userId
                ORDER BY c.sortOrder
            UNION
                SELECT c.capabilityId, c.key
                FROM `{$this->_prefix}users_roles` AS ur
                JOIN `{$this->_prefix}roles_capabilities` AS rc
                    ON (ur.roleId = rc.roleId)
                JOIN `{$this->_prefix}capabilities` AS c
                    ON (rc.capabilityId = c.capabilityId)
                WHERE ur.contextId = $contextId
                  AND ur.userId = $userId
                ORDER BY c.sortOrder
        ");
    }

    /**
     * @param int $contextId
     * @param int $userId
     * @param string $capabilityKey
     * @return bool
     */
    public function hasCapability($contextId, $userId, $capabilityKey)
    {
        $capabilityId = $this->fetchId('capabilities', $capabilityKey);
        if ($capabilityId === false) {
            return false;
        }
        $userId    = (int)$userId;
        $contextId = (int)$contextId;
        $matchingRows = $this->_db->fetchAll("
                SELECT 1
                FROM `{$this->_prefix}users_capabilities` AS uc
                WHERE uc.capabilityId = $capabilityId
                  AND uc.contextId = $contextId
                  AND uc.userId = $userId
            UNION
                SELECT 1
                FROM `{$this->_prefix}users_roles` AS ur
                JOIN `{$this->_prefix}roles_capabilities` AS rc
                    ON (ur.roleId = rc.roleId)
                WHERE rc.capabilityId = $capabilityId
                  AND ur.contextId = $contextId
                  AND ur.userId = $userId
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