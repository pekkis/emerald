<?php
class Emerald_Application_Resource_Server extends Zend_Application_Resource_Db
{
	
	public function init()
	{
		
		
		$options = $this->getOptions();
		
		
		Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Smtp($options['smtp']));
		
		date_default_timezone_set($options['timezone']);
		
	}
	
	
	
 
}