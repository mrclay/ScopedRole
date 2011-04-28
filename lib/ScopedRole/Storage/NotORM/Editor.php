<?php

namespace ScopedRole;

class Storage_NotORM_Editor implements Storage_IEditor {
    
    public function __construct(Storage_NotORM $storage)
    {
        $this->_storage = $storage;
        $this->_prefix = $storage->getPrefix();
        $this->_pdo = $storage->getPdo();
        $this->_orm = $storage->getOrm();
    }

    /**
     * @param string $key
     * @return int
     */
    public function createContextType($key) {
        $row = $this->_orm->contextType()->insert(array(
            'key' => $key
        ));
        return $row['id'];
    }

    /**
     * @param string $key
     * @param int $typeId
     * @return int
     */
    public function createContext($key, $contextTypeId)
    {
        $row = $this->_orm->context()->insert(array(
            'key' => $key,
            'id_contextType' => $contextTypeId,
        ));
        return $row['id'];
    }

    /**
     * @param string $key
     * @param int $sortOrder
     * @return int
     */
    public function createRole($key, $sortOrder)
    {
        $row = $this->_orm->role()->insert(array(
            'key' => $key,
            'sortOrder' => $sortOrder,
        ));
        return $row['id'];
    }

    /**
     * @param string $key
     * @param bool $isSuitableForRole
     * @param int $sortOrder
     * @return int
     */
    public function createCapability($key, $isSuitableForRole, $sortOrder)
    {
        $row = $this->_orm->capability()->insert(array(
            'key' => $key,
            'isSuitableForRole' => $isSuitableForRole,
            'sortOrder' => $sortOrder,
        ));
        return $row['id'];
    }

    /**
     * @param int $roleId
     * @param int $capabilityId
     * @return int
     */
    public function addCapability($roleId, $capabilityId)
    {
        $row = $this->_orm->role_capability()->insert(array(
            'id_role' => $roleId,
            'id_capability' => $capabilityId,
        ));
        return $row['id'];
    }

    /**
     * @param int $roleId
     * @param int $capabilityId
     * @return bool
     */
    public function removeCapability($roleId, $capabilityId)
    {
        $row = $this->_orm->role_capability()
                          ->where(array(
                              'id_role' => $roleId,
                              'id_capability' => $capabilityId,
                          ))
                          ->fetch();
        if ($row) {
            $row->delete();
            return true;
        }
        return false;
    }
    

    /**
     * @param int $roleId
     * @param int $userId
     * @param int $contextId
     * @return int
     */
    public function grantRole($roleId, $userId, $contextId)
    {
        $row = $this->_orm->user_role()->insert(array(
            'id_role' => $roleId,
            'id_user' => $userId,
            'id_context' => $contextId,
        ));
        return $row['id'];
    }

    /**
     * @param int $roleId
     * @param int $userId
     * @param int $contextId
     * @return bool
     */
    public function revokeRole($roleId, $userId, $contextId)
    {
        $row = $this->_orm->user_role()
                          ->where(array(
                              'id_role' => $roleId,
                              'id_user' => $userId,
                              'id_context' => $contextId,
                          ))
                          ->fetch();
        if ($row) {
            $row->delete();
            return true;
        }
        return false;
    }

    /**
     * @param int $capabilityId
     * @param int $userId
     * @param int $contextId
     * @return int|false
     */
    public function grantCapability($capabilityId, $userId, $contextId)
    {
        $row = $this->_orm->user_capability()->insert(array(
            'id_capability' => $capabilityId,
            'id_user' => $userId,
            'id_context' => $contextId,
        ));
        return $row['id'];
    }

    /**
     * @param int $capabilityId
     * @param int $userId
     * @param int $contextId
     * @return bool
     */
    public function revokeCapability($capabilityId, $userId, $contextId)
    {
        $row = $this->_orm->user_capability()->where(array(
            'id_capability' => $capabilityId,
            'id_user' => $userId,
            'id_context' => $contextId,
        ));
        if ($row) {
            $row->delete();
            return true;
        }
        return false;
    }

    /**
     * @var Storage_NotORM
     */
    protected $_storage;

    /**
     * @var string
     */
    protected $_prefix;

    /**
     * @var \NotORM
     */
    protected $_orm;

    /**
     * @var \PDO
     */
    protected $_pdo;
}