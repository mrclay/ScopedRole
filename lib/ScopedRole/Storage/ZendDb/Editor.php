<?php

namespace ScopedRole;

class Storage_ZendDb_Editor implements Storage_IEditor {
    
    public function __construct(Storage_ZendDb $storage)
    {
        $this->_storage = $storage;
        $this->_db = $storage->getDb();
    }

    /**
     * @param string $key
     * @return int
     */
    public function createContextType($key) {
        $table = $this->_storage->getTable('contextTypes');
        $row = $table->fetchRow($this->_db->quoteInto('key = ?', $key));
        if ($row) {
            throw new Exception('Context type already exists with key "' . $key . '".');
        }
        $row = $table->createRow(array('key' => $key));
        return $row->contextTypeId;
    }

    /**
     * @param string $key
     * @param int $typeId
     * @return int
     */
    public function createContext($key, $contextTypeId)
    {
        $table = $this->_storage->getTable('contexts');
        $row = $table->fetchRow($this->_db->quoteInto('key = ?', $key));
        if ($row) {
            throw new Exception('Context already exists with key "' . $key . '".');
        }
        $row = $table->createRow(array(
            'key' => $key,
            'contextTypeId' => $contextTypeId
        ));
        return $row->contextId;
    }

    /**
     * @param string $key
     * @param int $sortOrder
     * @return int
     */
    public function createRole($key, $sortOrder)
    {
        $table = $this->_storage->getTable('roles');
        $row = $table->fetchRow($this->_db->quoteInto('key = ?', $key));
        if ($row) {
            throw new Exception('Role already exists with key "' . $key . '".');
        }
        $row = $table->createRow(array(
            'key' => $key,
            'sortOrder' => $sortOrder
        ));
        return $row->roleId;
    }

    /**
     * @param string $key
     * @param bool $isSuitableForRole
     * @param int $sortOrder
     * @return int
     */
    public function createCapability($key, $isSuitableForRole, $sortOrder)
    {
        $table = $this->_storage->getTable('capabilities');
        $row = $table->fetchRow($this->_db->quoteInto('key = ?', $key));
        if ($row) {
            throw new Exception('Capability already exists with key "' . $key . '".');
        }
        $row = $table->createRow(array(
            'key' => $key,
            'isSuitableForRole' => $isSuitableForRole,
            'sortOrder' => $sortOrder,
        ));
        return $row->capabilityId;
    }

    /**
     * @param int $roleId
     * @param int $capabilityId
     * @return int
     */
    public function addCapability($roleId, $capabilityId)
    {
        $table = $this->_storage->getTable('roles_capabilities');
        $roleId       = (int)$roleId;
        $capabilityId = (int)$capabilityId;
        $row = $table->fetchRow("WHERE roleId = $roleId AND capabilityId = $capabilityId");
        if (! $row) {
            $row = $table->createRow(array(
                'roleId' => $roleId,
                'capabilityId' => $capabilityId,
            ));
        }
        return $row->id;
    }

    /**
     * @param int $roleId
     * @param int $capabilityId
     * @return bool
     */
    public function removeCapability($roleId, $capabilityId)
    {
        $table = $this->_storage->getTable('roles_capabilities');
        $roleId       = (int)$roleId;
        $capabilityId = (int)$capabilityId;
        $row = $table->fetchRow("WHERE roleId = $roleId AND capabilityId = $capabilityId");
        if ($row) {
            return (bool) $row->delete();
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
        $table = $this->_storage->getTable('users_roles');
        $roleId    = (int)$roleId;
        $userId    = (int)$userId;
        $contextId = (int)$contextId;
        $row = $table->fetchRow("WHERE roleId = $roleId AND userId = $userId AND contextId = $contextId");
        if (! $row) {
            $row = $table->createRow(array(
                'roleId' => $roleId,
                'userId' => $userId,
                'contextId' => $contextId,
            ));
        }
        return $row->id;
    }

    /**
     * @param int $roleId
     * @param int $userId
     * @param int $contextId
     * @return bool
     */
    public function revokeRole($roleId, $userId, $contextId)
    {
        $table = $this->_storage->getTable('users_roles');
        $roleId    = (int)$roleId;
        $userId    = (int)$userId;
        $contextId = (int)$contextId;
        $row = $table->fetchRow("WHERE roleId = $roleId AND userId = $userId AND contextId = $contextId");
        if ($row) {
            return (bool) $row->delete();
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
        $table = $this->_storage->getTable('users_capabilities');
        $capabilityId = (int)$capabilityId;
        $userId       = (int)$userId;
        $contextId    = (int)$contextId;
        $row = $table->fetchRow("WHERE capabilityId = $capabilityId AND userId = $userId AND contextId = $contextId");
        if (! $row) {
            $row = $table->createRow(array(
                'capabilityId' => $capabilityId,
                'userId' => $userId,
                'contextId' => $contextId,
            ));
        }
        return $row->id;
    }

    /**
     * @param int $capabilityId
     * @param int $userId
     * @param int $contextId
     * @return bool
     */
    public function revokeCapability($capabilityId, $userId, $contextId)
    {
        $table = $this->_storage->getTable('users_capabilities');
        $capabilityId = (int)$capabilityId;
        $userId       = (int)$userId;
        $contextId    = (int)$contextId;
        $row = $table->fetchRow("WHERE capabilityId = $capabilityId AND userId = $userId AND contextId = $contextId");
        if ($row) {
            return (bool) $row->delete();
        }
        return false;
    }

    /**
     * @var Storage_ZendDb
     */
    protected $_storage;

    /**
     * @return \Zend_Db_Adapter_Abstract
     */
    public function getDb()
    {
        return $this->_db;
    }
}