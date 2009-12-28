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
	
	
	public function getUsersIn(Core_Model_GroupItem $group)
	{
		$tbl = new Core_Model_DbTable_User();
		$sel = $tbl->getAdapter()->select()->from("user", "*");
		$sel->join('user_ugroup', "user.id = user_ugroup.user_id AND user_ugroup.ugroup_id = {$group->id}", null);
		$res = $tbl->getAdapter()->fetchAll($sel, null, Zend_Db::FETCH_ASSOC);
		
		$users = new ArrayIterator();
		foreach($res as $row) {
			$users->append(new Core_Model_UserItem($row));
		}
		return $users;
		
		
	}
	
	
}