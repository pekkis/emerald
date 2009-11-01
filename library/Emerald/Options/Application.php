<?php
/**
 * Application options
 *
 */
class Emerald_Options_Application extends Emerald_Options_Abstract
{
	/**
	 * Option table
	 *
	 * @var Zend_Db_Table_Abstract
	 */
	private $_optionTbl;
	
	private $_customer;
	
	public function __construct(Emerald_Application_Customer $customer)
	{
				
		
		$this->_customer = $customer;
		
		try {
			$this->_optionTbl = Emerald_Model::get('Application_Option');	
		} catch(Exception $e) {
			die('soo');
		}
		
	}
	
	
	protected function _get($key)
	{
		$row = $this->_optionTbl->find($key)->current();
		return ($row) ? $row->strvalue : false; 
	}
	
	
	/**
	 * Modifies Db, as needed, in the destructa phase
	 *
	 */
	public function __destruct()
	{
				
		foreach($this->_newOptions as $key) {
			$row = $this->_optionTbl->createRow(array('identifier' => $key, 'strvalue' => $this->get($key)));
			$row->save();
		}
		
		foreach($this->_dirtyOptions as $key) {
			$where = $this->_optionTbl->getAdapter()->quoteInto('identifier = ?', $key);
			$this->_optionTbl->update(array('strvalue' => $this->get($key)), $where);
		}
		
		
		foreach($this->_deleteOptions as $key) {
			$where = $this->_optionTbl->getAdapter()->quoteInto('identifier = ?', $key);
			$this->_optionTbl->delete($where);
		}
		
		
	}
	
	
}