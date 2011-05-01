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
     * @param string $title
     * @return int
     */
    public function createContextType($title = 'default')
    {
        $table = $this->_prefix . 'contextType';
        $row = $this->_orm->{$table}()->insert(array(
            'title' => $title
        ));
        return $row['id'];
    }

    /**
     * @param string $title
     * @param int $typeId
     * @return Context
     */
    public function createContext($title = 'default', $contextTypeId = null)
    {
        $table = $this->_prefix . 'context';
        $data['title'] = $title;
        if ($contextTypeId) {
            $data['id_contextType'] = $contextTypeId;
        }
        $row = $this->_orm->{$table}()->insert($data);
        return new Context($this->_storage, $row['id']);
    }

    /**
     * @param string $title
     * @param int $sortOrder
     * @return int
     */
    public function createRole($title, $sortOrder = null)
    {
        $table = $this->_prefix . 'role';
        $data['title'] = $title;
        if ($sortOrder) {
            $data['sortOrder'] = $sortOrder;
        }
        $row = $this->_orm->{$table}()->insert($data);
        return $row['id'];
    }

    /**
     * @param string $title
     * @param bool $isSuitableForRole
     * @param int $sortOrder
     * @return int
     */
    public function createCapability($title, $isSuitableForRole = true, $sortOrder = null)
    {
        $table = $this->_prefix . 'capability';
        $data = array(
            'title' => $title,
            'isSuitableForRole' => $isSuitableForRole,
        );
        if ($sortOrder) {
            $data['sortOrder'] = $sortOrder;
        }
        $row = $this->_orm->{$table}()->insert($data);
        return $row['id'];
    }

    /**
     * @param int $roleId
     * @param int $capabilityId
     * @return int
     */
    public function addCapability($roleId, $capabilityId)
    {
        $table = $this->_prefix . 'role_capability';
        $row = $this->_orm->{$table}()->insert(array(
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
        $table = $this->_prefix . 'role_capability';
        $row = $this->_orm->{$table}()
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
        $table = $this->_prefix . 'user_role';
        $row = $this->_orm->{$table}()->insert(array(
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
        $table = $this->_prefix . 'user_role';
        $row = $this->_orm->{$table}()
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
        $table = $this->_prefix . 'user_capability';
        $row = $this->_orm->{$table}()->insert(array(
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
        $table = $this->_prefix . 'user_capability';
        $row = $this->_orm->{$table}()->where(array(
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
     * @param string $title
     * @return Context|null
     */
    public function getContextByTitle($title = 'default')
    {
        $table = $this->_prefix . 'context';
        $row = $this->_orm->{$table}()->where('title', $title)->fetch();
        return ($row)
            ? new Context($this->_storage, $row['id'])
            : null;
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