<?php
class Core_Model_Shard
{

	const ACTIVE = 1;
	const INSERTABLE = 2;
	
	
	/**
	 * Returns table
	 * 
	 * @return Zend_Db_Table_Abstract
	 */
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new Core_Model_DbTable_Shard();
		}
		return $table;
	}
	
	
	/**
	 * Finds item with primary key
	 * 
	 * @param $id
	 * @return Core_Model_ShardItem
	 */
	public function find($id)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($id)->current();
		return ($row) ? new Core_Model_ShardItem($row->toArray()) : false;
	}
	
	
	/**
	 * Finds item with name
	 * 
	 * @param $id
	 * @return Core_Model_ShardItem
	 */
	public function findByName($name)
	{
		$tbl = $this->getTable();
		$row = $tbl->fetchRow(array('name = ?' => $name));
		return ($row) ? new Core_Model_ShardItem($row->toArray()) : false;
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
			$iter->append(new Core_Model_ShardItem($row));
		}
		return $iter;
	}	
	

	
	
	
	public function findByIdentifier($identifier)
	{
		return (is_numeric($identifier) ? $this->find($identifier) : $this->findByName($identifier));
	}
	
	
}
?>