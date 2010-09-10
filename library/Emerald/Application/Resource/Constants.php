<?php
class Emerald_Application_Resource_Constants extends Zend_Application_Resource_ResourceAbstract
{
	
	
	public function init()
	{

		$options = $this->getOptions();
		
		foreach($options as $constant => $value) {
			define($constant, $value);
		}
		
	}
	
	
	
}