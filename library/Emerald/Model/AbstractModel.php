<?php
abstract class Emerald_Model_AbstractModel
{
	protected $_injectables = array();

	protected $_rawInjectables;
	
	protected static $_table = null; 
	
		
	public function getRawInjectables()
	{
		if(!$this->_rawInjectables) {
									
			$this->_rawInjectables = $this->_getRawInjectables();
			
		}
		
		return $this->_rawInjectables;
	}
	
	
	protected function _getRawInjectables()
	{
		$rawInjectables = array();
		if($table = static::$_table) {
			$rawInjectables['table'] = function() use ($table) { return new $table; };
		}
		return $rawInjectables;
	}
	
	
	public function getInjectable($injectable)
	{
		if(isset($this->_injectables[$injectable])) {
			return $this->_injectables[$injectable];
		} else {
			$rawInjectables = $this->getRawInjectables();
			if(!isset($rawInjectables[$injectable])) {
				throw new Emerald_Model_Exception("Injectable '{$injectable}' not found.");
			}
			$this->_injectables[$injectable] = $rawInjectables[$injectable]();
			return $this->_injectables[$injectable]; 			
		}
	}
		
	
	public function __call($method, $args)
	{
		if(substr($method, 0, 3) == 'get') {
			$injectable = lcfirst(substr($method, 3));
			return $this->getInjectable($injectable);
		}
		
		throw new Emerald_Model_Exception("Magic method '{$method}' not callable.");
		
	}
	
	
	
	
	
	
}
