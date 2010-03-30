<?php
class EmCore_Model_NewsItem
{
	
	
	/**
	 * Returns table
	 * 
	 * @return Zend_Db_Table_Abstract
	 */
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new EmCore_Model_DbTable_NewsItem();
		}
		return $table;
	}
	
	
	/**
	 * Finds item with primary key
	 * 
	 * @param $id
	 * @return EmCore_Model_NewsItemItem
	 */
	public function find($id)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($id)->current();
		return ($row) ? new EmCore_Model_NewsItemItem($row->toArray()) : false;
	}
	
	
	
	
	public function save(EmCore_Model_NewsItemItem $item)
	{
		if(!is_numeric($item->id)) {
			$item->id = null;
		}
		
		$tbl = $this->getTable();
				
		if(!$item->id || !$row = $tbl->find($item->id)->current()) {
			$row = $tbl->createRow();
		}
		$row->setFromArray($item->toArray());
		$row->save();
		
		$item->setFromArray($row->toArray());
	}
	
	
		
	public function delete(EmCore_Model_NewsItemItem $item)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($item->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		$row->delete();
	}
	
	
}
?>