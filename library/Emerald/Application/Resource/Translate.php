<?php
class Emerald_Application_Resource_Translate extends Zend_Application_Resource_Translate
{
	
	
	public function init()
	{
		$options = $this->getOptions();

		$options['data'] = Emerald_Server::getInstance()->getDb();
		
		$this->setOptions($options);

		return parent::init();
				
	}
	
	
	
}
