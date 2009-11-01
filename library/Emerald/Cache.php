<?php
/**
 * Cache helper class
 *
 */
class Emerald_Cache
{
	
	
	/**
	 * Returns page cache
	 *
	 * @return Zend_Cache_Frontend_Page
	 */
	public static function getPage()
	{
		static $cache;
		if(!$cache) {
			
			$frontendOptions = array(
	    		'automatic_serialization' => true,
	    	);
			$backendOptions  = array(
	    		'cache_db_complete_path' => Emerald_Server::getInstance()->getRoot() . '/tmp/cache.sqlite'
			);
			
			$cache = Zend_Cache::factory('Core', 'Sqlite', $frontendOptions, $backendOptions);
			
			
		}
		
		return $cache;
		
	}
	
	
	
	/**
	 * Gets generic core cache object (med memcache). Stores f.ex table metadata.
	 *
	 * @return Zend_Cache_Core
	 */
	public static function getGeneric()
	{
		static $cache;
		if(!$cache) {
			$frontendOptions = array(
	    		'automatic_serialization' => true
	    	);
			$backendOptions  = array(
	    	);
			$cache = Zend_Cache::factory('Core', 'Memcached', $frontendOptions, $backendOptions);
		}
		return $cache;		
	}
	
	
	/**
	 * Returns generic APC cache
	 *
	 * @return Zend_Cache_Core
	 */
	public static function getApc()
	{
		static $cache;
		if(!$cache) {
			$frontendOptions = array(
	    	);
			$backendOptions  = array(
	    	);
			$cache = Zend_Cache::factory('Core', 'Apc', $frontendOptions, $backendOptions);
		}
		return $cache;		
	}
	
	
}
?>