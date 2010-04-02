<?php 

$start = microtime(true);

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

function _peksu_autoload($what) {

	require_once str_replace('_', '/', $what) . '.php';
	
	
}

// require_once "Zend/Loader/Autoloader.php";
// Zend_Loader_Autoloader::getInstance()->setDefaultAutoloader('_peksu_autoload');

                                         
/** Zend_Application */
require_once 'Zend/Application.php';


// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/emerald.ini'
);



$options = $application->getOptions();
if(isset($options['emerald']['constant'])) {
	
	foreach($options['emerald']['constant'] as $key => $value) {
		define('EMERALD_' . $key, $value);
	}
}




// define('EMERALD_URL_BASE', (isset($options['resources']['frontcontroller']['baseUrl'])) ? $options['resources']['frontcontroller']['baseUrl'] : '' );

try {

	$application->getBootstrap()
	->bootstrap('server')
	->bootstrap('modules')
	->bootstrap('customer')
	->bootstrap('db')
	->bootstrap('customerdb')
	->bootstrap('cache')
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

	$front = Zend_Controller_Front::getInstance();
	
	// $front->getDispatcher()->setParam('useDefaultControllerAlways', true);
	

	
	$lus = $application->run();
	
	
	
	$response = $front->getResponse();
	
	/*
	$cache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
	if($cache instanceof Emerald_Cache_Backend_Memcached) {
		$memcached = $cache->getBackend()->getMemcached();
		$request = $front->getRequest();
		$cacheKey = $cache->getOption('cache_id_prefix') . $request->getServer('REQUEST_URI');
		$memcached->set($cacheKey, $response->__toString(), 100);
	}
	*/
	
	echo $response;	
	
	
	$end = microtime(true) - $start;
	

	// echo $end;
	
} catch(Exception $e) {
	echo "<pre>Emerald threw you with an exception: " . $e . "</pre>"; 
	
	Zend_Debug::dump($e->getMessage());
	die();
	
	Zend_Debug::dump($e->getTrace());
		
	die('');
}
