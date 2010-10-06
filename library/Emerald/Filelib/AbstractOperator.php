<?php

namespace Emerald\Filelib;

/**
 * Operates on folders
 * 
 * @package Emerald_Filelib
 * @author pekkis
 * 
 */
abstract class AbstractOperator
{
    /**
     * Cache prefix
     * 
     * @var string
     */
    protected $_cachePrefix = '';
    
    protected $_filelib;
    
    public function __construct(\Emerald\Filelib\FileLibrary $filelib)
    {
        $this->_filelib = $filelib;
    }
    
    /**
     * Returns backend
     *
     * @return \Emerald\Filelib\Backend\Backend
     */
    public function getBackend()
    {
        return $this->getFilelib()->getBackend();
    }

    /**
     * Returns filelib
     *
     * @return \Emerald\Filelib\FileLibrary
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }
    
    
    /**
     * Returns cache
     * 
     * @return \Emerald\Base\Cache\Cache
     */
    public function getCache()
    {
        return $this->getFilelib()->getCache();
    }

    /**
     * Returns cache identifier
     * 
     * @param mixed $id Id
     * @return string
     */
    public function getCacheIdentifier($id)
    {
        if(is_array($id)) {
            $id = implode('_', $id);
        }
        return $this->_cachePrefix . '_' . $id;
    }


    /**
     * Tries to load folder from cache, returns object on success.
     * 
     * @param mixed $id
     * @return mixed 
     */
    public function findCached($id) {
        return $this->getCache()->load($this->getCacheIdentifier($id));
    }


    /**
     * Clears cache for id
     * 
     * @param mixed $id
     */
    public function clearCached($id)
    {
        $this->getCache()->remove($this->getCacheIdentifier($id));
    }


    /**
     * Stores folder to cache
     * 
     * @param mixed $id
     * @param mixed $data
     */
    public function storeCached($id, $data)
    {
        $this->getCache()->save($this->getCacheIdentifier($id), $data);
    }


}