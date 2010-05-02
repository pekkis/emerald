<?php 
function _emerald_autoload($what) {
	require_once str_replace('_', '/', $what) . '.php';
}

set_include_path(realpath(dirname(__FILE__) . '/../library'));

require_once 'Emerald/Timer.php';

$timer = Emerald_Timer::getTimer('emerald');
$timer->time('emerald start');

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

$timer->time('application init start');

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/emerald.ini'
);

$timer->time('application init end');

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

$timer->time('bootstrapping start');

try {
	$application->getBootstrap()->bootstrap();
	
	$timer->time('application run start');

	$application->run();
	// $response = Zend_Controller_Front::getInstance()->getResponse();
	// echo $response;	

	$timer = Emerald_Timer::getTimer('emerald');
	$timer->time('emerald end');
	// echo $timer;
	// die();
	
		
	// echo $end;
	
} catch(Exception $e) {
	echo "<pre>Emerald threw you with an exception: " . $e . "</pre>"; 
	
	Zend_Debug::dump($e->getMessage());
	die();
	
	Zend_Debug::dump($e->getTrace());
		
	die('');
}
