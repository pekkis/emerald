<?php
/**
 * @author pekkis
 *
 * @todo Caching of non-anonymous pages is baaaaad :)
 *
 */
class Emerald_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract
{

    protected $backends;

    protected $frontends;

    public function init()
    {

        $cm = new Emerald_Cache_Manager();
        Zend_Registry::set('Emerald_CacheManager', $cm);

        $opts = $this->getOptions();

        foreach($opts['frontend'] as $key => $frontend) {
            $backend = $this->_getBackend($frontend['backend']);
            $cache = Zend_Cache::factory($frontend['class'], $backend, $frontend['options']);
            $cm->setCache($key, $cache);
        }


        $defaultCache = $cm->getCache('default');

        Zend_Db_Table_Abstract::setDefaultMetadataCache($defaultCache);
        Zend_Date::setOptions(array('cache' => $defaultCache));
        Zend_Translate::setCache($defaultCache);
        Zend_Locale::setCache($defaultCache);
        Zend_Currency::setCache($defaultCache);


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



}