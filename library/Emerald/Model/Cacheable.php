<?php
/**
 * Cacheable base model
 * 
 * @author pekkis
 * @package Emerald_Model
 *
 */
abstract class Emerald_Model_Cacheable extends Emerald_Model_AbstractModel
{
    private $_cache;

    protected $_cachePrefix;

    /**
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        if(!$this->_cache) {
            $this->_cachePrefix = get_class($this);
            $this->_cache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
        }
        return $this->_cache;
    }


    public function getCacheIdentifier($id)
    {
        if(is_array($id)) {
            $id = implode('_', $id);
        }
        return $this->_cachePrefix . '_' . $id;
    }


    public function findCached($id) {
        return $this->getCache()->load($this->getCacheIdentifier($id));
    }


    public function clearCached($id)
    {
        $this->getCache()->remove($this->getCacheIdentifier($id));
    }


    public function storeCached($id, $data)
    {
        $this->getCache()->save($data, $this->getCacheIdentifier($id));
    }


}