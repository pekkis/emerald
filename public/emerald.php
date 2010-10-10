<?php
// gc_enable();

// start profiling
// xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);



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
 
 

// require_once "Emerald/Debug/Timer.php";
// $timer = Emerald_Base_Debug_Timer::getTimer('luss');

// $timer->time('application start');

// Create application, bootstrap, and run
require_once "Emerald/Common/Application.php";

$application = new Emerald_Common_Application(
APPLICATION_ENV,
APPLICATION_PATH . '/configs/emerald.ini',
array(
    	'type' => APPLICATION_CONFIG_CACHE,
    	'key' => 'application_config_' . getenv('EMERALD_CUSTOMER')
)
);

// $timer->time('application done');

$options = $application->getOptions();
if($options['pluginCache']) {
    $classFileIncCache = APPLICATION_PATH . '/../data/pluginLoaderCache.php';
    if (file_exists($classFileIncCache)) {
        include_once $classFileIncCache;
    }
    Zend_Loader_PluginLoader::setIncludeFileCache($classFileIncCache);
}

// $timer->time('plugin cache done');

try {
    $application->getBootstrap()->bootstrap();

    // $timer->time('bootstrap done');

    // $timer = Emerald\Debug\Timer::getTimer('lus');
    // $timer->time('start');


    $application->run();
    
    // Zend_Debug::dump(Zend_Loader_Autoloader::getInstance()->getAutoloaders());

} catch(Exception $e) {
    echo "<pre>Emerald threw you with an exception: " . $e . "</pre>";

    Zend_Debug::dump($e->getMessage());
    die();

    Zend_Debug::dump($e->getTrace());

    die('');
}

// $timer->time('end');
// echo $timer;

// stop profiler
// $xhprof_data = xhprof_disable();

//
// Saving the XHProf run
// using the default implementation of iXHProfRuns.
//

// $XHPROF_ROOT = '/wwwroot/php/lib/php';

// include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
// include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";

// $xhprof_runs = new XHProfRuns_Default();

// Save the run under a namespace "xhprof_foo".
//
// **NOTE**:
// By default save_run() will automatically generate a unique
// run id for you. [You can override that behavior by passing
// a run id (optional arg) to the save_run() method instead.]
//
// $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");

// echo "<a href='http://syopalahti.axis-of-evil.org/xhprof_html/index.php?run=$run_id&source=xhprof_foo'>xooo</a>";



