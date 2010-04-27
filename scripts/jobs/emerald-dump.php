<?php
set_include_path(realpath(dirname(__FILE__) . '/../../library'));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH',
              realpath(dirname(__FILE__) . '/../../application'));

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
    APPLICATION_PATH . '/configs/emerald.ini'
);

putenv("EMERALD_CUSTOMER=default");

echo "\n\n";

try {

	$application->getBootstrap()
	->bootstrap('server')
	->bootstrap('customer')
	->bootstrap('db')
	->bootstrap('customerdb');
	
	$db = $application->getBootstrap()->getResource('customer')->getDb();
	
	$conf = $db->getConfig();
	
		
	// $db->query("USE fiksuhuuto3");
	
	$res = $db->fetchCol("SHOW TABLES");
	
	
	$tables = array();
	foreach($res as $row) {
		if(!preg_match("/^emerald/", $row)) {
			$tables[] = $row;
		}		
	}
	
	// Zend_Debug::Dump($res);

	Zend_Debug::dump($tables);
	
	
	$customer = getenv('EMERALD_CUSTOMER');
	
	$cmd = "mysqldump -uroot -pg04753m135 --add-drop-table=false --no-create-db=true --no-create-info=true --complete-insert=true {$conf['dbname']} " . implode(' ', $tables);
	
	echo $cmd;
	
	echo "\n\n";
	
	
} catch(Exception $e) {
	
	echo $e;
	
	die('xooxer');
}


// die('done');



?>
