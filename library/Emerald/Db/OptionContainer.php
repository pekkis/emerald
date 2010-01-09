<?php
class Emerald_Db_OptionContainer
{
	
	
	protected $_table;
	
	protected $_whereConditions = array();
	
	protected $_keyColumn = 'identifier';
	
	protected $_valueColumn = 'strvalue';

	protected $_select = null;
	

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

	
		
	
	
	
	public function getKeyColumn()
	{
		return $this->_keyColumn;
	}
	
	
	public function getValueColumn()
	{
		return $this->_valueColumn;
	}
	
	
	public function setTable(Zend_Db_Table_Abstract $table)
	{
		$this->_table = $table;
	}
	
	
	/**
	 * Returns table
	 * 
	 * @return Zend_Db_Table_Abstract
	 */
	public function getTable()
	{
		return $this->_table;
	}
	
	
	public function setWhereConditions(array $conds)
	{
		$this->_whereConditions = $conds;
	}
	
	
	public function getWhereConditions()
	{
		return $this->_whereConditions;
	}
	
	
	
	
	public function deset($key)
	{
		unset($this->_options[$key]);
		$this->_deleteOptions[$key] = $key;
	}
	
	
	
	
		/**
	 * Gets option
	 *
	 * @param string $key Option identifier
	 * @param string $default Default if null
	 * @return string Option valie
	 */
	public function get($key)
	{
		if(!array_key_exists($key, $this->_options)) {
			$sel = $this->_getSelect();
			$sel->where("{$this->getKeyColumn()} = ?", $key);
			$this->_fetch($sel);		
		}
		
		if(key_exists($key, $this->_options)) {
			return $this->_options[$key];	
		} else {
			return false;
		}
		
	}
	
	
	public function getOptions()
	{
		$this->_fetch($this->_getSelect());
		return $this->_options;
	}
	
	
	protected function _fetch($sel)
	{
		$keyc = $this->getKeyColumn();
		$valuec = $this->getValueColumn();
		
		$res = $this->getTable()->getAdapter()->fetchAll($sel);

		// Zend_Debug::dump($res);
		
		foreach($res as $row) {
			$this->_options[$row->$keyc] = $row->$valuec;
		}
		
		// Zend_Debug::Dump($this->_options);
		
	}
	
	
	protected function _getSelect()
	{
		if(!$this->_select) {
			$sel = $this->getTable()->getAdapter()->select();
			$sel->from($this->getTable()->info('name'), array($this->getKeyColumn(), $this->getValueColumn()));
			
			foreach($this->getWhereConditions() as $key => $value) {
				$sel->where("{$key} = ?", $value);
			}
			
			$this->_select = $sel;
		}
				
		return clone $this->_select;
		
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
		
				
		if($oldValue !== false) {
			
			$arr = $this->getWhereConditions();
			$arr += array($this->getKeyColumn() => $key);
			
			$where = array();
			foreach($arr as $key2 => $value2) {
				$where[] = $this->getTable()->getAdapter()->quoteInto("{$key2} = ?", $value2);
			}
			$where = implode(" AND ", $where); 
			$this->getTable()->update(array($this->getValueColumn() => $value), $where);
			
		} else {
			
			$arr = $this->getWhereConditions();
			$arr += array($this->getKeyColumn() => $key, $this->getValueColumn() => $value);
			$row = $this->getTable()->createRow();
			$row->setFromArray($arr);
			$row->save();
			
		}
		
		$this->_options[$key] = $value;
		
	}
	
	
	public function __unset($key)
	{
		return $this->deset($key);
	}
	
	
	
	public function __get($key)
	{
		return $this->get($key);
	}	
	
	
	public function __set($key, $value)
	{
		return $this->set($key, $value);
	}
	
	
	
	
		/**
	 * Modifies Db, as needed, in the destructa phase
	 *
	 */
	public function __destruct()
	{

		return;
		
		
		foreach($this->_deleteOptions as $key) {
			
			$arr = $this->getWhereConditions();
			$arr += array($this->getKeyColumn() => $key);
			
			$where = array();
			foreach($arr as $key => $value) {
				$where[] = $this->getTable()->getAdapter()->quoteInto("{$key} = ?", $value);
			}
			$where = implode(" AND ", $where); 
			
			$this->getTable()->delete($where);
		}
		
		
	}
	
	
}