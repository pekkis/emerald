<?php
class Core_Model_Form
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
			$table = new Core_Model_DbTable_Form();
		}
		return $table;
	}
	
	
	/**
	 * Finds item with primary key
	 * 
	 * @param $id
	 * @return Core_Model_FormItem
	 */
	public function find($id)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($id)->current();
		return ($row) ? new Core_Model_FormItem($row->toArray()) : false;
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
			$iter->append(new Core_Model_FormItem($row));
		}
		return $iter;
	}	
	
	
	public function getFields(Core_Model_FormItem $item)
	{
		$tbl = new Core_Model_DbTable_FormField();
		$rows = $tbl->fetchAll(array('form_id = ?' => $item->id), "order_id ASC");
		$iter = new ArrayIterator();
		foreach($rows as $row) {
			$iter->append(new Core_Model_FormFieldItem($row));
		}
		return $iter;
	}
	
	
	public function getOrderIdForNewField(Core_Model_FormItem $item)
	{
		$tbl = new Core_Model_DbTable_FormField();
		$max = $tbl->getAdapter()->fetchOne("SELECT MAX(order_id) FROM emerald_form_field WHERE form_id = ?", array($item->id));
		return $max + 1;
	}
	
	
	
	
	public function save(Core_Model_FormItem $item)
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

	
	public function saveField(Core_Model_FormFieldItem $item)
	{
		if(!is_numeric($item->id)) {
			$item->id = null;
		}
		
		$tbl = new Core_Model_DbTable_FormField();
		$row = $tbl->find($item->id)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}
						
		$row->setFromArray($item->toArray());
		$row->save();
		$item->setFromArray($row->toArray());
				
	}
	
	
	public function deleteField(Core_Model_FormFieldItem $item)
	{
		$tbl = new Core_Model_DbTable_FormField();
		$row = $tbl->find($item->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		$row->delete();
	}
	
	
	public function delete(Core_Model_FormItem $item)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($item->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		$row->delete();
	}
	
	
	
}
