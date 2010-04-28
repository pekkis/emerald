<?php
class EmCore_Model_Group
{
	const GROUP_ANONYMOUS = 1;
	const GROUP_ROOT = 2;
	
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new EmCore_Model_DbTable_Ugroup();
		}
		
		return $table;
	}
	

	public function find($id)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($id)->current();
		return ($row) ? new EmCore_Model_GroupItem($row->toArray()) : false;
	}
	
	
	public function findAll()
	{
		$rows = $this->getTable()->fetchAll(array(), 'id ASC');
		$groups = new ArrayIterator();
		foreach($rows as $row) {
			$groups->append(new EmCore_Model_GroupItem($row));
		}
		
		return $groups;
	}
	
	
	public function getUsersIn(EmCore_Model_GroupItem $group)
	{
		$tbl = new EmCore_Model_DbTable_User();
		$sel = $tbl->getAdapter()->select()->from("emerald_user", "*");
		$sel->join('emerald_user_ugroup', "emerald_user.id = emerald_user_ugroup.user_id AND emerald_user_ugroup.ugroup_id = {$group->id}", null);
		
		$res = $tbl->getAdapter()->fetchAll($sel, null, Zend_Db::FETCH_ASSOC);
		
		$users = new ArrayIterator();
		foreach($res as $row) {
			$users->append(new EmCore_Model_UserItem($row));
		}
		return $users;
		
		
	}
	
	
		
	public function save(EmCore_Model_GroupItem $group)
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
		
		$group->setFromArray($row->toArray());
		
		$acl = Zend_Registry::get('Emerald_Acl');
		if($acl->hasRole($group)) {
			$acl->removeRole($group);	
		}
				
	}

	
	public function delete(EmCore_Model_GroupItem $group)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($group->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		
		$row->delete();
		
		$acl = Zend_Registry::get('Emerald_Acl');
		if($acl->hasRole($group)) {
			$acl->removeRole($group);	
		}
			
		
		
	}
	
	
	
}