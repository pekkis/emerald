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
	->bootstrap('modules')
	->bootstrap('customer')
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
	->bootstrap('filelibplugins');
	;
		
	$application->run();
	
	
} catch(Exception $e) {
	echo "<pre>Emerald threw you with an exception: " . $e . "</pre>"; 
	die('');
}
