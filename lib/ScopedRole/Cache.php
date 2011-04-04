<?php

namespace ScopedRole;

class Cache {

    public function __construct(\Zend_Cache_Core $cache = null, $prefix = 'scrl_')
    {
        $this->setCache($cache);
        $this->_cache->setConfig(array('prefix' => $prefix));
    }

    /**
     * @return \Zend_Cache_Core
     */
    public function getCache()
    {
        return $this->_cache;
    }

    /**
     * @param \Zend_Cache_Core $cache
     */
    public function setCache(\Zend_Cache_Core $cache = null)
    {
        if (! $cache) {
            $cache = \Zend_Cache::factory('Core', 'Apc');
        }
    }

    /**
     * @var \Zend_Cache_Core
     */
    protected $_cache;
}