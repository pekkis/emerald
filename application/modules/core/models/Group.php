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
	

	public function find($id)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($id)->current();
		return ($row) ? new Core_Model_GroupItem($row->toArray()) : false;
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
	
	
	public function getUsersIn(Core_Model_GroupItem $group)
	{
		$tbl = new Core_Model_DbTable_User();
		$sel = $tbl->getAdapter()->select()->from("user", "*");
		$sel->join('user_ugroup', "{$tbl->getAdapter()->quoteIdentifier("user")}.id = user_ugroup.user_id AND user_ugroup.ugroup_id = {$group->id}", null);
		
		$res = $tbl->getAdapter()->fetchAll($sel, null, Zend_Db::FETCH_ASSOC);
		
		$users = new ArrayIterator();
		foreach($res as $row) {
			$users->append(new Core_Model_UserItem($row));
		}
		return $users;
		
		
	}
	
	
		
	public function save(Core_Model_GroupItem $group)
	{
				
		if(!is_numeric($group->id)) {
			$group->id = null;
		}
		
		$tbl = $this->getTable();
		
		$row = $tbl->find($group->id)->current();
		if(!$row) {
			$row = $tbl->createRow();
		}
						
		$row->setFromArray($group->toArray());
		$row->save();
		
		$group->id = $row->id;
		
	}

	
	public function delete(Core_Model_GroupItem $group)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($group->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		
		$row->delete();
		
	}
	
	
	
}