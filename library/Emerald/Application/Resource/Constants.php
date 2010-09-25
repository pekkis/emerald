<?php
/**
 * Constant setting resource
 * 
 * @author pekkis
 * @package Emerald_Application
 *
 */
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