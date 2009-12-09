<?php
set_include_path(realpath(dirname(__FILE__) . '/../../../../../library'));

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH',
              realpath(dirname(__FILE__) . '/../../../../../application'));

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


$opts = new Zend_Console_Getopt(
	array(
		'help|h' => 'Help!',
		'before|b=s' => 'Bootstrap before filelib, ; as separator',
		'after|a=s' => 'Bootstrap after filelib, ; as separator',
		'symlinks|s' => 'Recreate symlinks',
	)
);

try {
	$opts->parse();
	if ($opts->getOption('h')) {
   		die( $opts->getUsageMessage() );
	}
} catch (Zend_Console_Getopt_Exception $e) {
   	die( $opts->getUsageMessage() );
}

$before = explode(";", $opts->b);

$after = explode(";", $opts->a);

foreach($before as $b) {
	$application->bootstrap($b);
}

$application->bootstrap('db');

foreach($after as $a) {
	$application->bootstrap($a);
}

$recreateSymlinks = $opts->s;


$filelib = Zend_Registry::get('Emerald_Filelib');

$files = $filelib->findAllFiles();

echo "\n\n";

foreach($files as $file) {
	
	echo "Recreate versions for #{$file->id}, of mimetype '{$file->mimetype}' and type '{$file->getType()}'\n";
			
	foreach($filelib->getPlugins() as $plugin) {
		
		if($plugin instanceof Emerald_Filelib_Plugin_VersionProvider_Interface && $plugin->providesFor($file)) {

			try {
				echo "\tProcessing version '{$plugin->getIdentifier()}' => ";
				$plugin->createVersion($file);
				echo "Great success!";
			} catch(Exception $e) {
				echo "EPIC FAIL!";
			}		
			echo "\n";
		
			if($recreateSymlinks) {
				$filelib->getSymlinker()->deleteSymlink($file);
				$filelib->getSymlinker()->createSymlink($file);
			}
		
		}
	}
	
	echo "\n";
}



?>