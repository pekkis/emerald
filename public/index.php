<?php 
set_include_path(realpath(dirname(__FILE__) . '/../library'));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH',
              realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV',
              (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV')
                                         : 'production'));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

try {

	$application->getBootstrap()
	->bootstrap('server')
	->bootstrap('modules')
	->bootstrap('customer')
	->bootstrap('cache')
	->bootstrap('db')
	->bootstrap('customerdb')
	->bootstrap('router')
	->bootstrap('session')
	->bootstrap('acl')
	->bootstrap('locale')
	->bootstrap('user')
	->bootstrap('view')
	->bootstrap('translate')
	->bootstrap('layout')
	->bootstrap('filelib')
	->bootstrap('filelibplugins')
	->bootstrap('misc');
		
	$lus = $application->run();
	
	$front = Zend_Controller_Front::getInstance();
	$response = $front->getResponse();
	
	$cache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
	$memcached = $cache->getBackend()->getMemcached();
	
	$request = $front->getRequest();
	
	
	$cacheKey = $cache->getOption('cache_id_prefix') . $request->getServer('REQUEST_URI');
	
	$memcached->set($cacheKey, $response->__toString(), 100);
	
	echo $response;	
	
} catch(Exception $e) {
	echo "<pre>Emerald threw you with an exception: " . $e . "</pre>"; 
	
	Zend_Debug::dump($e->getMessage());
	die();
	
	Zend_Debug::dump($e->getTrace());
		
	die('');
}
