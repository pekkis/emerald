<?php
class Emerald_Application_Resource_Cache extends Zend_Application_Resource_ResourceAbstract
{
	
	protected $_globalBackend;
	
	
	public function init()
	{				
		$defaultCache = $this->getBootstrap()->getResource('cachemanager')->getCache('default');
		
		Zend_Db_Table_Abstract::setDefaultMetadataCache($defaultCache);		
		Zend_Date::setOptions(array('cache' => $defaultCache));
		Zend_Translate::setCache($defaultCache);
		Zend_Locale::setCache($defaultCache);	
	}
	
	
	
}