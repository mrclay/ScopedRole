<?php

namespace ScopedRole;

class UserContext {

    protected $_userId;
    protected $_contextId;

    /**
     * @var IStorage
     */
    protected $_storage;

    protected $_roles;
    protected $_capabilities;

    public function __construct(Core $core, $contextId, $userId)
    {
        $this->_storage = $core->getStorage();
        $this->_contextId = $contextId;
        $this->_roles = $this->_storage->fetchRoles($contextId, $userId);
        $this->_capabilities = $this->_storage->fetchCapabilities($contextId, $userId);
    }

    public function hasCapability($title)
    {
        return in_array($title, array_values($this->_capabilities));
    }

    public function hasRole($title)
    {
        return in_array($title, array_values($this->_roles));
    }
}