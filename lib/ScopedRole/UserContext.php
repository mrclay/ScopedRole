<?php

namespace ScopedRole;

/**
 * Value object to store user's roles and capabilities. You can store this in a session
 * and use isFresh()/getRefreshed() to make sure you rebuild it every so often (in case
 * the underlying DB changes)
 */
class UserContext {

    protected $_userId;
    protected $_contextId;
    protected $_roles;
    protected $_capabilities;
    protected $_creationTime;

    protected function __construct($userId, $contextId, $roles, $capabilities, $currentTime)
    {
        $this->_userId = $userId;
        $this->_contextId = $contextId;
        $this->_creationTime = $currentTime;
        $this->_roles = $roles;
        $this->_capabilities = $capabilities;
    }

    /**
     * Make a UserContext value object
     * @param IStorage $storage
     * @param int $userId
     * @param int $contextId
     * @param int $currentTime
     * @return UserContext
     */
    public static function make(IStorage $storage, $userId, $contextId = 1, $currentTime = null)
    {
        if (! $currentTime) {
            $currentTime = time();
        }
        $roles = $storage->fetchRoles($contextId, $userId);
        $capabilities = $storage->fetchCapabilities($contextId, $userId);
        return new self($userId, $contextId, $roles, $capabilities, $currentTime);
    }

    /**
     * @param IStorage $storage
     * @return UserContext
     */
    public function getRefreshed(IStorage $storage)
    {
        return self::make($storage, $this->_userId, $this->_contextId);
    }

    /**
     * @param string $title
     * @return bool
     */
    public function hasCapability($title)
    {
        return in_array($title, $this->_capabilities);
    }

    /**
     * @param string $title
     * @return bool
     */
    public function hasRole($title)
    {
        return in_array($title, $this->_roles);
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->_roles;
    }

    /**
     * @return array
     */
    public function getCapabilities()
    {
        return $this->_capabilities;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->_userId;
    }

    /**
     * @return int
     */
    public function getContextId()
    {
        return $this->_contextId;
    }

    /**
     * Should this value object be considered "fresh"?
     * @param int $ttl in seconds
     * @param int $currentTime in seconds
     * @return bool
     */
    public function isFresh($ttl = 900, $currentTime = null)
    {
        if (! $currentTime) {
            $currentTime = time();
        }
        $age = round($currentTime - $this->_creationTime);
        return ($age < $ttl);
    }
}