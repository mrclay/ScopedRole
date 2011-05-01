<?php

namespace ScopedRole;

class Storage_ZendDb_Editor implements Storage_IEditor {
    
    public function __construct(Storage_ZendDb $storage)
    {
        $this->_storage = $storage;
        $this->_db = $storage->getDb();
    }

    /**
     * @param string $title
     * @return int
     */
    public function createContextType($title = 'default') {
        $table = $this->_storage->getTable('contextType');
        $row = $table->fetchRow($this->_db->quoteInto('title = ?', $title));
        if ($row) {
            throw new Exception('Context type already exists with title "' . $title . '".');
        }
        $row = $table->createRow(array('title' => $title));
        $row->save();
        return $row->id;
    }

    /**
     * @param string $title
     * @param int $typeId
     * @return Context
     */
    public function createContext($title = 'default', $contextTypeId = null)
    {
        $table = $this->_storage->getTable('context');
        $row = $table->fetchRow($this->_db->quoteInto('title = ?', $title));
        if ($row) {
            throw new Exception('Context already exists with title "' . $title . '".');
        }
        $data['title'] = $title;
        if ($contextTypeId) {
            $data['id_contextType'] = $contextTypeId;
        }
        $row = $table->createRow($data);
        $row->save();
        return new Context($this->_storage, $row->id);
    }

    /**
     * @param string $title
     * @param int $sortOrder
     * @return int
     */
    public function createRole($title, $sortOrder = null)
    {
        $table = $this->_storage->getTable('role');
        $row = $table->fetchRow($this->_db->quoteInto('title = ?', $title));
        if ($row) {
            throw new Exception('Role already exists with title "' . $title . '".');
        }
        $data['title'] = $title;
        if ($sortOrder) {
            $data['sortOrder'] = $sortOrder;
        }
        $row = $table->createRow($data);
        $row->save();
        return $row->id;
    }

    /**
     * @param string $title
     * @param bool $isSuitableForRole
     * @param int $sortOrder
     * @return int
     */
    public function createCapability($title, $isSuitableForRole = true, $sortOrder = null)
    {
        $table = $this->_storage->getTable('capability');
        $row = $table->fetchRow($this->_db->quoteInto('title = ?', $title));
        if ($row) {
            throw new Exception('Capability already exists with title "' . $title . '".');
        }
        $data = array(
            'title' => $title,
            'isSuitableForRole' => $isSuitableForRole,
        );
        if ($sortOrder) {
            $data['sortOrder'] = $sortOrder;
        }
        $row = $table->createRow($data);
        $row->save();
        return $row->id;
    }

    /**
     * @param int $roleId
     * @param int $capabilityId
     * @return int
     */
    public function addCapability($roleId, $capabilityId)
    {
        $table = $this->_storage->getTable('role_capability');
        $roleId       = (int)$roleId;
        $capabilityId = (int)$capabilityId;
        $select = $table->select()->where("id_role = $roleId AND id_capability = $capabilityId");
        $row = $table->fetchRow($select);
        if (! $row) {
            $row = $table->createRow(array(
                'id_role' => $roleId,
                'id_capability' => $capabilityId,
            ));
            $row->save();
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
        $table = $this->_storage->getTable('role_capability');
        $roleId       = (int)$roleId;
        $capabilityId = (int)$capabilityId;
        $select = $table->select()->where("id_role = $roleId AND id_capability = $capabilityId");
        $row = $table->fetchRow($select);
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
        $table = $this->_storage->getTable('user_role');
        $roleId    = (int)$roleId;
        $userId    = (int)$userId;
        $contextId = (int)$contextId;
        $select = $table->select()->where("id_role = $roleId AND id_user = $userId AND id_context = $contextId");
        $row = $table->fetchRow($select);
        if (! $row) {
            $row = $table->createRow(array(
                'id_role' => $roleId,
                'id_user' => $userId,
                'id_context' => $contextId,
            ));
            $row->save();
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
        $table = $this->_storage->getTable('user_role');
        $roleId    = (int)$roleId;
        $userId    = (int)$userId;
        $contextId = (int)$contextId;
        $select = $table->select()->where("id_role = $roleId AND id_user = $userId AND id_context = $contextId");
        $row = $table->fetchRow($select);
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
        $table = $this->_storage->getTable('user_capability');
        $capabilityId = (int)$capabilityId;
        $userId       = (int)$userId;
        $contextId    = (int)$contextId;
        $select = $table->select()->where("id_capability = $capabilityId AND id_user = $userId AND id_context = $contextId");
        $row = $table->fetchRow($select);
        if (! $row) {
            $row = $table->createRow(array(
                'id_capability' => $capabilityId,
                'id_user' => $userId,
                'id_context' => $contextId,
            ));
            $row->save();
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
        $table = $this->_storage->getTable('user_capabilitie');
        $capabilityId = (int)$capabilityId;
        $userId       = (int)$userId;
        $contextId    = (int)$contextId;
        $select = $table->select()->where("id_capability = $capabilityId AND id_user = $userId AND id_context = $contextId");
        $row = $table->fetchRow($select);
        if ($row) {
            return (bool) $row->delete();
        }
        return false;
    }

    /**
     * @param string $title
     * @return Context|null
     */
    public function getContextByTitle($title = 'default')
    {
        $table = $this->_storage->getTable('context');
        $select = $table->select()->columns('id')->where('title = ?', $title);
        $row = $table->fetchRow($select);
        return ($row)
            ? new Context($this->_storage, $row->id)
            : null;
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