<?php
/**
 * User options
 *
 */
class Emerald_Options_User extends Emerald_Options_Abstract
{
	
	
	/**
	 * User
	 *
	 * @var Emerald_User
	 */
	private $_user;

	/**
	 * Option table
	 *
	 * @var Zend_Db_Table_Abstract
	 */
	private $_optionTbl;
	
	
	
	public function __construct(Emerald_User $user)
	{
		$this->_user = $user;
		$this->_optionTbl = Emerald_Model::get('User_Option');
	}
		
	
	protected function _get($key)
	{
		$row = $this->_optionTbl->find($this->_user->id, $key)->current();
		return ($row) ? $row->strvalue : false;
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
		
		if($oldValue && is_null($value)) {
			 $this->_deleteOptions[$key] = $key;
		} elseif(!$oldValue && $value) {
			$this->_newOptions[$key] = $key;
		} elseif($oldValue != $value) {
									
			$this->_dirtyOptions[$key] = $key;
		}
	}
	
	
	
	/**
	 * Modifies Db, as needed, in the destructa phase
	 *
	 */
	public function __destruct()
	{
		foreach($this->_newOptions as $key) {
			$row = $this->_optionTbl->createRow(array('user_id' => $this->_user->id, 'identifier' => $key, 'strvalue' => $this->get($key)));
			$row->save();
		}
		
		foreach($this->_dirtyOptions as $key) {
			$where = "user_id = " . $this->_user->getTable()->getAdapter()->quote($this->_user->id) . " AND identifier = " . $this->_user->getTable()->getAdapter()->quote($key); 
			$this->_optionTbl->update(array('strvalue' => $this->get($key)), $where);
		}
		
		
		foreach($this->_deleteOptions as $key) {
			$where = "user_id = " . $this->_user->getTable()->getAdapter()->quote($this->_user->id) . " AND identifier = " . $this->_user->getTable()->getAdapter()->quote($key);
			$this->_optionTbl->delete($where);
		}
		
		
	}
	
	
}