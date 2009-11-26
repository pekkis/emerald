<?php 
abstract class Emerald_Model_AbstractItem
{

	protected $_data = array();
	
	public function __construct(array $data = array())
	{
		foreach($data as $key => $value) {
			$this->$key = $value;			
		}
	}
	
	public function __set($key, $value)
	{
		$this->_data[$key] = $value;
	} 
	
	
	public function __get($key)
	{
		if(!isset($this->_data[$key])) {
			throw new Emerald_Model_Exception("Field '{$key}' not set");
		}
		return $this->_data[$key];
	}
	
	
	public function toArray()
	{
		return $this->_data;
	}
	
	
	
	public function __call($func, $args)
	{
				
		if(preg_match('/^get/', $func)) {
			$filter = new Zend_Filter_Word_CamelCaseToUnderscore();
			$field = lcfirst(substr($func, 3));
			$field = $filter->filter($field);
			
			return $this->$field;
			
		}		
		
		
		
		throw new Emerald_Model_Exception("Method '{$func}' does not exist");
		
	}
	
	

}