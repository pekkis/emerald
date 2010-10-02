<?php
/**
 * Cache resource
 * 
 * @author pekkis
 * @package Emerald_Application
 * @todo The whole Zend Cache is soooo retarded. This does not work as it should work.
 *
 */
class Emerald_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract
{

    protected $backends;

    protected $frontends;

    /**
     * @return Emerald_Cache_Manager
     */
    public function init()
    {
                
        $opts = $this->getOptions();
                
        $cm = $this->getBootstrap()->bootstrap('cachemanager')->getResource('cachemanager');
        Zend_Registry::set('Emerald_CacheManager', $cm);

        if(isset($opts['framework'])) {
            foreach($opts['framework'] as $key => $cache) {
                $method = '_init' . ucfirst($key);
                $this->$method($cm->getCache($cache));
            }
        }

        return $cm;

    }

}