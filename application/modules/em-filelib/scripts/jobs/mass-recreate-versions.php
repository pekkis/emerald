<?php
ob_start();

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

require_once "Zend/Console/Getopt.php";
require_once "Zend/Debug.php";

$opts = new Zend_Console_Getopt(
array(
        'ini|i=s' => 'ini file name(f.ex application.ini)',
        'env|e=s' => 'env variables',
        'bootstrap|b=s' => 'Bootstrap sequence, use \'auto\' for automatic',
        'help|h' => 'Help!',
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


/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
APPLICATION_ENV,
APPLICATION_PATH . '/configs/' . $opts->ini
);


$env = $opts->env;

if($env) {
	$env = explode(',', $env);
	foreach($env as $e) {
		putenv($e);
	}
}



$bootstraps = explode(",", $opts->bootstrap);


foreach($bootstraps as $b) {
    
	if($b == 'auto') {
		$application->bootstrap();
	   break;	
	} else {
		$application->bootstrap($b);
	}

}

$recreateSymlinks = $opts->s;

/** @var Emerald\Filelib\FileLibrary */
$filelib = Zend_Registry::get('Emerald_Filelib');

$files = $filelib->file()->findAll();

echo "\n\n";

foreach($files as $file) {

	echo "Recreate versions for #{$file->id} ('{$file->name}'), mime '{$file->mimetype}',type '{$file->getType()}', profile '{$file->profile}'\n";
		
	foreach($filelib->getPlugins() as $plugin) {

		if($plugin instanceof \Emerald\Filelib\Plugin\VersionProvider\VersionProviderInterface && $filelib->file()->hasVersion($file, $plugin->getIdentifier())) {

			try {
				echo "\tProcessing version '{$plugin->getIdentifier()}' => ";
				$plugin->createVersion($file);
				echo "Great success!";
			} catch(Exception $e) {
				echo "EPIC FAIL with {$e->getMessage()}";
			}
			echo "\n";

		}

		if($recreateSymlinks) {
			$filelib->getLinker()->deleteSymlink($file);
			$filelib->getLinker()->createSymlink($file);
		}
	}

	echo "\n";
}



?>