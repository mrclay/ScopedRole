<?php

namespace ScopedRole;

/**
 * Value object to store user's roles and capabilities. You can store this in a session
 * and use isFresh()/getRefreshed() to make sure you rebuild it every so often (in case
 * the underlying DB changes)
 */
class VO_UserContext {

    protected $_userId;
    protected $_contextId;
    protected $_roles = array();
    protected $_capabilities = array();
    protected $_creationTime;
    protected $_runtimeRoles = array();
    protected $_runtimeCapabilities = array();

    protected function __construct(array $spec)
    {
        foreach (get_object_vars($this) as $k => $v) {
            $specKey = substr($k, 1);
            if (isset($spec[$specKey])) {
                $this->{$k} = $spec[$specKey];
            }
        }
    }

    /**
     * Make a UserContext value object
     * @param IStorage $storage
     * @param int $userId
     * @param int $contextId
     * @param int $currentTime
     * @return UserContext
     */
    public static function make(IStorage $storage, array $spec = array())
    {

        $spec = array_merge(array(
                'userId' => 0,
                'contextId' => 1,
                'creationTime' => time(),
                'runtimeRoles' => array(),
                'runtimeCapabilities' => array(),
            ), $spec);
        $spec['roles'] = $storage->fetchUserRoles($spec['contextId'], $spec['userId']);
        $spec['capabilities'] = $storage->fetchUserCapabilities($spec['contextId'], $spec['userId']);
        foreach ($spec['runtimeRoles'] as $roleTitle) {
            $roleId = $storage->fetchId('role', $roleTitle);
            if ($roleId) { // known role
                $spec['roles'][$roleId] = $roleTitle;
                $caps = $storage->fetchRoleCapabilities($roleId);
                foreach ($caps as $capId => $capTitle) {
                    $spec['capabilities'][$capId] = $capTitle;
                }
            } else { // role not in DB, don't give it a numeric key
                $spec['roles'][$roleTitle] = $roleTitle;
            }
        }
        foreach ($spec['runtimeCapabilities'] as $capTitle) {
            $capId = $storage->fetchId('capability', $capTitle);
            if ($capId) {
                $spec['capabilities'][$capId] = $capTitle;
            } else {
                $spec['capabilities'][$capTitle] = $capTitle;
            }
        }
        return new self($spec);
    }

    /**
     * @param IStorage $storage
     * @return UserContext
     */
    public function getRefreshed(IStorage $storage)
    {
        $spec = array(
            'userId' => $this->_userId,
            'contextId' => $this->_contextId,
            'runtimeRoles' => $this->_runtimeRoles,
            'runtimeCapabilities' => $this->_runtimeCapabilities,
        );
        return self::make($storage, $spec);
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
     * @return array
     */
    public function getRuntimeRoles()
    {
        return $this->_runtimeRoles;
    }

    /**
     * @return array
     */
    public function getPersistedRoles()
    {
        return \array_diff($this->_roles, $this->_runtimeRoles);
    }

    /**
     * @return array
     */
    public function getRuntimeCapabilities()
    {
        return $this->_runtimeCapabilities;
    }

    /**
     * @return array
     */
    public function getPersistedCapabilities()
    {
        return array_diff($this->_capabilities, $this->_runtimeCapabilities);
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