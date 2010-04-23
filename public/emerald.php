<?php 
function _emerald_autoload($what) {
	require_once str_replace('_', '/', $what) . '.php';
}

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
                                         
require_once "Zend/Loader/Autoloader.php";
Zend_Loader_Autoloader::getInstance()->setDefaultAutoloader('_emerald_autoload');
                                         
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

if($options['pluginCache']) {
	$classFileIncCache = APPLICATION_PATH . '/../data/pluginLoaderCache.php';
	if (file_exists($classFileIncCache)) {
    	include_once $classFileIncCache;
	}
	Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
}

try {
	$application->getBootstrap()
	->bootstrap('server')
	->bootstrap('modules')
	->bootstrap('customer')
	->bootstrap('db')
	->bootstrap('customerdb')
	->bootstrap('cache')
	->bootstrap('session')
	->bootstrap('emacl')
	->bootstrap('translate')
	->bootstrap('locale')
	->bootstrap('router')
	->bootstrap('emuser')
	->bootstrap('view')
	->bootstrap('layout')
	->bootstrap('filelib')
	//->bootstrap('misc')
	->bootstrap()
	;
	
	$application->run();
	// $response = Zend_Controller_Front::getInstance()->getResponse();
	// echo $response;	

	$end = microtime(true) - $start;
	
	echo $end;
	
} catch(Exception $e) {
	echo "<pre>Emerald threw you with an exception: " . $e . "</pre>"; 
	
	Zend_Debug::dump($e->getMessage());
	die();
	
	Zend_Debug::dump($e->getTrace());
		
	die('');
}
