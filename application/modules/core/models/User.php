<?php
class Core_Model_User
{
	const USER_ANONYMOUS = 1;
	
	/**
	 * Returns table
	 * 
	 * @return Core_Model_DbTable_User
	 */
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new Core_Model_DbTable_User();
		}
		return $table;
	}
	

	public function find($id)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($id)->current();
		return ($row) ? new Core_Model_UserItem($row->toArray()) : false;
	}
	
	
	public function findAnonymous()
	{
		$user = new Core_Model_UserItem();
		$user->id = Core_Model_User::USER_ANONYMOUS;
		$user->setGroups(array(new Core_Model_GroupItem(array('id' => Core_Model_Group::GROUP_ANONYMOUS))));

		return $user;
	}
	
	
	public function getGroupsFor(Core_Model_UserItem $user)
	{
		$tbl = new Core_Model_DbTable_Ugroup();
		$sel = $tbl->getAdapter()->select()->from("ugroup", "*");
		$sel->join('user_ugroup', "ugroup.id = user_ugroup.ugroup_id AND user_ugroup.user_id = {$user->id}", null);
		
		
		$res = $tbl->getAdapter()->fetchAll($sel, null, Zend_Db::FETCH_ASSOC);
		
		$groups = array();
		foreach($res as $row) {
			$groups[] = new Core_Model_GroupItem($row);
		}
		
		return $groups;
		
		
	}
	
	
	
}