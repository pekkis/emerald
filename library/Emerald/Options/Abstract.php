<?php
abstract class Emerald_Options_Abstract
{
	/**
	 * Cached options
	 *
	 * @var array
	 */
	protected $_options = array();
	
	/**
	 * Options to update
	 *
	 * @var array
	 */
	protected $_dirtyOptions = array();
	
	/**
	 * Options to delete
	 *
	 * @var array
	 */
	protected $_deleteOptions = array();
	
	/**
	 * Options to insert
	 *
	 * @var array
	 */
	protected $_newOptions = array();
	
	
	abstract protected function _get($key);
	
	
	
		/**
	 * Gets option
	 *
	 * @param string $key Option identifier
	 * @param string $default Default if null
	 * @return string Option valie
	 */
	public function get($key)
	{
		if(!isset($this->_options[$key])) {
			$this->_options[$key] = $this->_get($key);
		}
		return $this->_options[$key];
		
		
	}
	
	
	/**
	 * Sets option value
	 *
	 * @param string $key Key
	 * @param string $value Value
	 */
	public function set($key, $value)
	{
		$oldValue = $this->get($key);
		$this->_options[$key] = $value;
		
		if($oldValue !== false && !$value) {
			 $this->_deleteOptions[$key] = $key;
		} elseif($oldValue === false && $value) {
			$this->_newOptions[$key] = $key;
		} elseif($oldValue != $value) {
			$this->_dirtyOptions[$key] = $key;
		}
	}
	
	
}