<?php 
function _emerald_autoload($what) {
	require_once str_replace('_', '/', $what) . '.php';
}

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

// Define application config cache
defined('APPLICATION_CONFIG_CACHE')
    || define('APPLICATION_CONFIG_CACHE',
              (getenv('APPLICATION_CONFIG_CACHE') ? getenv('APPLICATION_CONFIG_CACHE')
                                         : 'none'));
                                         
require_once 'Emerald/Application.php';

// Create application, bootstrap, and run
$application = new Emerald_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/emerald.ini',
    array(
    	'type' => APPLICATION_CONFIG_CACHE,
    	'key' => 'application_config_' . getenv('EMERALD_CUSTOMER')
    )
);

$options = $application->getOptions();
if($options['pluginCache']) {
	$classFileIncCache = APPLICATION_PATH . '/../data/pluginLoaderCache.php';
	if (file_exists($classFileIncCache)) {
    	include_once $classFileIncCache;
	}
	Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
}

try {
	$application->getBootstrap()->bootstrap();
	$application->run();
} catch(Exception $e) {
	echo "<pre>Emerald threw you with an exception: " . $e . "</pre>"; 
	
	Zend_Debug::dump($e->getMessage());
	die();
	
	Zend_Debug::dump($e->getTrace());
		
	die('');
}
