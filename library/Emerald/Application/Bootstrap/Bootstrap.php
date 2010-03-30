<?php
class Emerald_Application_Bootstrap_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	
	public function addOptions($options)
	{
	    return $this->setOptions($this->mergeOptions($this->getOptions(), $options));
	}
	
	
}