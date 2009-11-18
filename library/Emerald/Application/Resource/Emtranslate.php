<?php
class Emerald_Application_Resource_Emtranslate extends Zend_Application_Resource_Translate
{
	
	
	public function init()
	{
		$options = $this->getOptions();

		$options['data'] = Emerald_Server::getInstance()->getDb()->getConfig();
		
		$this->setOptions($options);

		return parent::init();
				
	}
	
	
	
}
