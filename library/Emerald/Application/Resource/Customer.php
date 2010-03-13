<?php
class Emerald_Application_Resource_Customer extends Zend_Application_Resource_ResourceAbstract
{
	
	public function init()
	{
		// Console tools may define customer with putenv
		$customer = (getenv('EMERALD_CUSTOMER')) ? getenv('EMERALD_CUSTOMER') : $_SERVER['HTTP_HOST'];
		
		$this->getBootstrap()->bootstrap('frontcontroller');
		
		$path = APPLICATION_PATH . '/../customers/' . $customer ;
		if(is_dir($path)) {
			$customer = new Emerald_Application_Customer(realpath($path));
		}
		
		if(!$customer) {
			throw new Emerald_Exception("Customer not found");
		}
		
		$front = $this->getBootstrap()->getResource('frontcontroller');
		
		$config = $customer->getConfig();
		
		$config = $config->toArray();
		
		$this->getBootstrap()->addOptions($config);
		
		try {
		   	$front->addModuleDirectory($customer->getRoot() . '/modules');	
	    } catch(Exception $e) {
	       	// There aint no customer specific modules
	    }
		
	    Zend_Registry::set('Emerald_Customer', $customer);
	    
		return $customer;
		
	}
}