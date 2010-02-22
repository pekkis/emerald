<?php
class Emerald_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract
{
	
	protected $_globalBackend;
	
	
	public function init()
	{
		
		$cm = new Emerald_Cache_Manager();
		Zend_Registry::set('Emerald_CacheManager', $cm);		

		$opts = $this->getOptions();
				
		$backend = $this->getGlobalBackend();
		$globalCache = Zend_Cache::factory('Core', $backend, $opts['frontend']['default']['options']);		
		
		$cm->setCache('default', $globalCache);
				
		Zend_Db_Table_Abstract::setDefaultMetadataCache($globalCache);		
		Zend_Date::setOptions(array('cache' => $globalCache));
		Zend_Translate::setCache($globalCache);
		Zend_Locale::setCache($globalCache);	

		return $cm;				
	}
	
	
	
	
	public function getGlobalBackend()
	{
		if(!$this->_globalBackend) {
			$opts = $this->getOptions();
			$this->_globalBackend = Zend_Cache::_makeBackend($opts['backend']['default']['name'], $opts['backend']['default']['options'], true, true);
		}
		return $this->_globalBackend;		
	}
	
	
}