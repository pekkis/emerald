<?php
class Core_Model_Group
{
	const GROUP_ANONYMOUS = 1;
	const GROUP_ROOT = 2;
	
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new Core_Model_DbTable_Ugroup();
		}
		
		return $table;
	}
	
	
	
	public function findAll()
	{
		$rows = $this->getTable()->fetchAll(array(), 'id ASC');
		$groups = new ArrayIterator();
		foreach($rows as $row) {
			$groups->append(new Core_Model_GroupItem($row));
		}
		
		return $groups;
	}
	
	
}