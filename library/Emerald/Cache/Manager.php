<?php
class Emerald_Cache_Manager
{
	
	
	private $_caches = array();
	
	
	
	public function __construct()
	{
		
	}
	
	
	
	public function setCache($name, Zend_Cache_Core $cache)
	{
		$this->_caches[$name] = $cache;
	}
	
	
	public function getCache($name)
	{
		return $this->_caches[$name];
	}
		
	
	
}