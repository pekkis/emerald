<?php
class Core_Model_User
{
	const USER_ANONYMOUS = 0;
	
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

	
	public function findAll()
	{
		$rows = $this->getTable()->fetchAll(array(), 'email ASC');
		$iter = new ArrayIterator();
		foreach($rows as $row) {
			$iter->append(new Core_Model_UserItem($row));
		}
		return $iter;
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
		
	
	public function save(Core_Model_UserItem $user)
	{
				
		if(!is_numeric($user->id)) {
			$user->id = null;
		}
		
		$tbl = $this->getTable();
		
		$row = $tbl->find($user->id)->current();
		if(!$row) {
			$row = $tbl->createRow();
			$row->passwd = md5(uniqid());
		}
						
		$row->setFromArray($user->toArray());
		$row->save();
		
		$user->id = $row->id;
		
	}

	
	public function delete(Core_Model_UserItem $user)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($user->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		
		$row->delete();
		
	}
	
	
	
	public function setPassword(Core_Model_UserItem $user, $password)
	{
								
		$password = md5($password);
		
		$user->passwd = $password;
		$this->save($user);
	}
	
	
	public function setGroups(Core_Model_UserItem $user, $groups)
	{
	
		$tbl = new Core_Model_DbTable_UserGroup();
					
		$tbl->getAdapter()->beginTransaction();
		
		$tbl->delete($tbl->getAdapter()->quoteInto("user_id = ?", $user->id));
		
		if($groups) {
			foreach($groups as $key => $groupId) {
				$tbl->insert(array('user_id' => $user->id, 'ugroup_id' => $groupId));
			}
		}
		
		$tbl->getAdapter()->commit();
				
	}
	
	
}