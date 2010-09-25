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

        /*
         $naviModel = new EmCore_Model_Navigation();

         $navi = $naviModel->getNavigation();

         $navi = new RecursiveIteratorIterator($navi, RecursiveIteratorIterator::SELF_FIRST);

         // Zend_Debug::dump($navi);

         // Zend_Debug::dump($navi);

         $regexps = array();
         foreach($navi as $page) {
         $regexps["^{$page->uri}$"] = array(
         'cache' => ($page->cache_seconds) ? true : false,
         'specific_lifetime' => $page->cache_seconds,
         );
         	
         }

         $fopts = $opts['frontend']['page']['options'];
         $fopts['regexps'] = $regexps;

         $pageCache = Zend_Cache::factory('Page', $backend, $fopts);
         $cm->setCache('page', $pageCache);

         $pageCache->start();
         */

        return $cm;

    }


    protected function _getBackend($backend)
    {
        if(!isset($this->_backends[$backend])) {
            $opts = $this->getOptions();
            	
            if(!isset($opts['backend'][$backend]['options'])) {
                $opts['backend'][$backend]['options'] = array();
            }
            	
            $this->_backends[$backend] = Zend_Cache::_makeBackend($opts['backend'][$backend]['name'], $opts['backend'][$backend]['options'], true, true);
        }

        return $this->_backends[$backend];
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