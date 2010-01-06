<?php
class Core_Model_FormField
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
			$table = new Core_Model_DbTable_FormField();
		}
		return $table;
	}
	
	
	/**
	 * Finds item with primary key
	 * 
	 * @param $id
	 * @return Core_Model_FormFieldItem
	 */
	public function find($id)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($id)->current();
		return ($row) ? new Core_Model_FormFieldItem($row->toArray()) : false;
	}
	
	
	
	
	/**
	 * Finds all items
	 * 
	 * @return ArrayIterator
	 */
	public function findAll()
	{
		$rows = $this->getTable()->fetchAll(array(), 'name ASC');
		$iter = new ArrayIterator();
		foreach($rows as $row) {
			$iter->append(new Core_Model_FormFieldItem($row));
		}
		return $iter;
	}	
	
	
	public function save(Core_Model_FormFieldItem $item)
	{
		if(!is_numeric($item->id)) {
			$item->id = null;
		}
		
		$tbl = $this->getTable();
		
		$row = $tbl->find($item->id)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}
						
		$row->setFromArray($item->toArray());
		$row->save();
		
		$item->setFromArray($row->toArray());
				
	}

	
	public function delete(Core_Model_FormFieldItem $item)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($item->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		$row->delete();
	}
	
	
	
}
