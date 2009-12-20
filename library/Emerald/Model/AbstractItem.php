<?php 
/**
 * Abstract model item class
 * 
 * @package Emerald_Model
 * @author pekkis
 * 
 *
 */
abstract class Emerald_Model_AbstractItem
{

	/**
	 * @var array Item data
	 */
	protected $_data = array();
	
	/**
	 * Constructor fills item with data if specified. If an object is given, toArray is called.
	 * 
	 * @param object|array $data
	 */
	public function __construct($data = array())
	{
		if(is_object($data)) {
			$data = $data->toArray();
		}
		
		if(!is_array($data)) {
			throw new Emerald_Model_Exception('Supplied data must be an array');
		}
		
		foreach($data as $key => $value) {
			$this->$key = $value;			
		}
	}
	
	
	/**
	 * Returns an array representation of the item.
	 * 
	 * @return array
	 */
	public function toArray()
	{
		return $this->_data;
	}

	
	/**
	 * Sets from array
	 * 
	 * @param array $array Data
	 */
	public function setFromArray(array $array)
	{
		$this->_data = array_merge($this->_data, $array);
		
	}
	
	

	/**
	 * Automatic setter sets a field automagically
	 * 
	 * @param string $key Key
	 * @param mixed $value Value
	 */
	public function __set($key, $value)
	{
		$this->_data[$key] = $value;
	} 
	
	
	public function __isset($key)
	{
		return array_key_exists($key, $this->_data);
	}
	
	
	
	/**
	 * Automatic getter returns a field if it exists.
	 * 
	 * @param string $key Key
	 * @return mixed
	 * @throws Emerald_Model_Exception
	 */
	public function __get($key)
	{
		if(!array_key_exists($key, $this->_data)) {
			throw new Emerald_Model_Exception("Field '{$key}' not set");
		}
		return $this->_data[$key];
	}
	

	/**
	 * Automagic caller proxies unknown getX and setX invocations to fields. 
	 * 
	 * @param string $func
	 * @param array $args
	 * @return mixed
	 */
	public function __call($func, $args)
	{
		if(preg_match('/^get/', $func)) {
			$filter = new Zend_Filter_Word_CamelCaseToUnderscore();
			$field = lcfirst(substr($func, 3));
			$field = $filter->filter($field);
			return $this->$field;
		}		

		if(preg_match('/^set/', $func)) {
			$filter = new Zend_Filter_Word_CamelCaseToUnderscore();
			$field = lcfirst(substr($func, 3));
			$field = $filter->filter($field);
			$this->$field = $args[0];
		}		
		
		throw new Emerald_Model_Exception("Method '{$func}' does not exist");
	}
	
	
	

}