<?php
/**
 * Locale options
 *
 */
class Emerald_Options_Locale extends Emerald_Options_Abstract
{
	
	
	/**
	 * Locale
	 *
	 * @var Emerald_Locale
	 */
	private $_locale;

	/**
	 * Option table
	 *
	 * @var Zend_Db_Table_Abstract
	 */
	private $_optionTbl;
	
	public function __construct(Emerald_Db_Table_Row_Locale $locale)
	{
		$this->_locale = $locale;
		$this->_optionTbl = Emerald_Model::get('Locale_Option');
	}
		
	protected function _get($key)
	{
		$row = $this->_optionTbl->find($this->_locale->locale, $key)->current();
		return ($row) ? $row->strvalue : false;
	}
	
	
	/**
	 * Modifies Db, as needed, in the destructa phase
	 *
	 */
	public function __destruct()
	{
		foreach($this->_newOptions as $key) {
			$row = $this->_optionTbl->createRow(array('locale_locale' => $this->_locale->locale, 'identifier' => $key, 'strvalue' => $this->get($key)));
			$row->save();
		}
		
		foreach($this->_dirtyOptions as $key) {
			$where = "locale_locale = " . $this->_locale->getTable()->getAdapter()->quote($this->_locale->locale) . " AND identifier = " . $this->_locale->getTable()->getAdapter()->quote($key); 
			$this->_optionTbl->update(array('strvalue' => $this->get($key)), $where);
		}
		
		
		foreach($this->_deleteOptions as $key) {
			$where = "locale_locale = " . $this->_locale->getTable()->getAdapter()->quote($this->_locale->locale) . " AND identifier = " . $this->_locale->getTable()->getAdapter()->quote($key);
			$this->_optionTbl->delete($where);
		}
		
		
	}
	
	
}