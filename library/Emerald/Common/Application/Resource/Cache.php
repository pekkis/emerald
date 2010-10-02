<?php
/**
 * Cache resource
 * 
 * @author pekkis
 * @package Emerald_Common_Application
 * @todo The whole Zend Cache is soooo retarded. This does not work as it should work.
 *
 */
class Emerald_Common_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract
{

    protected $backends;

    protected $frontends;

    /**
     * @return Zend_Cache_Manager
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
    
    protected function _initTable(Zend_Cache_Core $cache)
    {
        Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
    }
    
    protected function _initDate(Zend_Cache_Core $cache)
    {
        Zend_Date::setOptions(array('cache' => $cache));
    }
    
    
    protected function _initTranslate(Zend_Cache_Core $cache)
    {
        Zend_Translate::setCache($cache);
    }
    
    protected function _initLocale(Zend_Cache_Core $cache)
    {
        Zend_Locale::setCache($cache);
    }
    
    
    protected function _initCurrency(Zend_Cache_Core $cache)
    {
        Zend_Currency::setCache($cache);
    }
    
    
    
            
    

}