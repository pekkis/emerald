<?php
class Emerald_Db_Table_Row_Locale extends Zend_Db_Table_Row_Abstract 
{

	private $_options;
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		
	    if (isset($config['data']) && isset($config['stored']) && $config['stored'] === true) {
	    	$this->_options = new Emerald_Options_Locale($this); 	    	
        }
				
	}
	

	public function getOption($key, $default = null)
    {
    	if(!$this->_options)
    		return $default;
    	
    	return $this->_options->get($key, $default);
    }
    
    
    public function setOption($key, $value)
    {
    	return $this->_options->set($key, $value);
    }
	

    
}