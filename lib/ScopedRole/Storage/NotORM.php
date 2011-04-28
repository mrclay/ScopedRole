<?php

namespace ScopedRole;

class Storage_NotORM implements IStorage {

    /**
     * @param \PDO $pdo
     * @param string $prefix
     */
    public function __construct(\PDO $pdo, $prefix = 'scrl_')
    {
        $this->_prefix = $prefix;
        $this->_pdo = $pdo;
        $structure = new NotORM_Structure_Convention(
            $primary = "id", // id
            $foreign = "id_%s", // e.g. id_role
            $table = "$prefix%s" // e.g. scrl_role
        );
        $this->_orm = new NotORM($pdo, $structure);
    }

    /**
     * @param string $table
     * @param string $key
     * @return int|false
     */
    public function fetchId($table, $key)
    {
        $row = $this->_orm->{$table}("key = ?", $key);
        return $row['id'];
    }

    /**
     * @return Storage_NotORM_Editor
     */
    public function getEditor()
    {
        static $editor = null;
        if ($editor === null) {
            $editor = new Storage_NotORM_Editor($this);
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
        $sql = "
            SELECT r.id, r.key
            FROM `{$this->_prefix}role` AS r
            JOIN `{$this->_prefix}user_role` AS ur
                ON (r.id = ur.id_role)
            WHERE ur.id_user = $userId AND ur.id_context = $contextId
            ORDER BY r.sortOrder
        ";
        return $this->_pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
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
        $sql = "
                SELECT c.id, c.key
                FROM `{$this->_prefix}user_capability` AS uc
                JOIN `{$this->_prefix}capability` AS c
                    ON (uc.id_capability = c.id)
                WHERE uc.id_context = $contextId
                  AND uc.id_user = $userId
                ORDER BY c.sortOrder
            UNION
                SELECT c.id, c.key
                FROM `{$this->_prefix}user_role` AS ur
                JOIN `{$this->_prefix}role_capability` AS rc
                    ON (ur.id_role = rc.id_role)
                JOIN `{$this->_prefix}capability` AS c
                    ON (rc.id_capability = c.id)
                WHERE ur.id_context = $contextId
                  AND ur.id_user = $userId
                ORDER BY c.sortOrder
        ";
        return $this->_pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $contextId
     * @param int $userId
     * @param string $capabilityKey
     * @return bool
     */
    public function hasCapability($contextId, $userId, $capabilityKey)
    {
        $capabilityId = $this->fetchId('capability', $capabilityKey);
        if ($capabilityId === false) {
            return false;
        }
        $userId    = (int)$userId;
        $contextId = (int)$contextId;
        $sql = "
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
        ";
        return $this->_pdo->query($sql)->rowCount() > 0;
    }

    /**
     * @var string
     */
    protected $_prefix;

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /**
     * @var \NotORM
     */
    protected $_orm;

    /**
     * @return \NotORM
     */
    public function getOrm()
    {
        return $this->_orm;
    }

    /**
     * @var \PDO
     */
    protected $_pdo;

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->_pdo;
    }

}