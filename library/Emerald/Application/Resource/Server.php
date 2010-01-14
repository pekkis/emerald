<?php
class Emerald_Application_Resource_Server extends Zend_Application_Resource_Db
{
	
	public function init()
	{
		
		$options = $this->getOptions();
		
		date_default_timezone_set($options['timezone']);
		
		
		Zend_Debug::dump($this->getOptions());
		
	}
	
	
	
 
}