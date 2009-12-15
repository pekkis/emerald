<?php
class Core_Model_LocaleItem extends Emerald_Model_AbstractItem
{
	
	private $_optionContainer;
	
	public function __toString()
	{
		return $this->locale;
	}
	
	
	public function getOptionContainer()
	{
		if(!$this->_optionContainer) {
			$this->_optionContainer = new Emerald_Options_Locale($this);
		}
		return $this->_optionContainer;
	}
	
	
	
	public function getOption($key, $default = null)
    {
    	return $this->getOptionContainer()->get($key, $default);
    }
    
    
    public function setOption($key, $value)
    {
    	return $this->getOptionContainer()->set($key, $value);
    }
	
	
	
}