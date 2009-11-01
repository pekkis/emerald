<?php

set_include_path(realpath(dirname(__FILE__) . '/../library'));

require_once "Zend/Loader/Autoloader.php";
Zend_Loader_Autoloader::getInstance()->registerNamespace('Emerald');



try {

	$server = Emerald_Server::getInstance();
	date_default_timezone_set($server->getConfig()->timezone);
		
	$request = new Zend_Controller_Request_Http();
	$response = new Zend_Controller_Response_Http();
	
	$front = Zend_Controller_Front::getInstance();
    $front->setRequest($request);
    $front->setResponse($response);
	
	
	// $front->setParam('useCaseSensitiveActions', false);
        
    $front->throwExceptions(false);
    $front->addModuleDirectory($server->getRoot() . '/modules');

    $customer = $server->findCustomer($request);
    
    if(!$customer) {
    	die('Customer not found!');
    }
    
    
    try {
	   	$front->addModuleDirectory($customer->getRoot() . '/modules');	
    } catch(Exception $e) {
       	// There aint no customer specific modules
    }
	
	
	$front->setDefaultControllerName('index');
	$front->returnResponse(true);
	
	// TODO: Where do the routes semantically go? To the server? App?
    $router = new Zend_Controller_Router_Rewrite();
    $router->addDefaultRoutes();
        
	$router->addRoute('locale',
	new Zend_Controller_Router_Route_Regex(
	'([a-z]{2,3}(_[A-Z]{2})?)',
	array('controller' => 'index', 'action' => 'index'),
	array(1 => 'locale')
	)
	);
        
	$router->addRoute('iisiurl',
	new Zend_Controller_Router_Route_Regex(
	'(([a-z]{2,3}(_[A-Z]{2})?)/(.*?))(\.(html|xml))?$',
	array('controller' => 'page', 'action' => 'view', 'render_type' => 'html'),
	array(1 => 'iisiurl', 6 => 'render_type')
	)
	);
        
	$router->addRoute('loginlocale',
	new Zend_Controller_Router_Route_Regex(
	'login(/([a-z]{2,3}))?',
	array('controller' => 'login', 'action' => 'index', 'locale' => 'en_US'),
	array(2 => 'locale')
	));        
        
	$front->setRouter($router);
	
	$app = Emerald_Application::getInstance();
	$app->setCustomer($customer);
	
	
	
	$response = $app->run();
	
	// Zend_Debug::dump($response);
	
	print $response;	
} catch(Exception $e) {
	echo "<pre>Emerald threw you with an exception: " . $e . "</pre>"; 
	die('');
}
