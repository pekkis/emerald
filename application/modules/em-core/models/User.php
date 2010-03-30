<?php
class EmCore_Model_User
{
	const USER_ANONYMOUS = 0;
	
	/**
	 * Returns table
	 * 
	 * @return EmCore_Model_DbTable_User
	 */
	public function getTable()
	{
		static $table;
		if(!$table) {
			$table = new EmCore_Model_DbTable_User();
		}
		return $table;
	}

	
	public function findAll()
	{
		$rows = $this->getTable()->fetchAll(array(), 'email ASC');
		$iter = new ArrayIterator();
		foreach($rows as $row) {
			$iter->append(new EmCore_Model_UserItem($row));
		}
		return $iter;
	}
		

	public function find($id)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($id)->current();
		return ($row) ? new EmCore_Model_UserItem($row->toArray()) : false;
	}
	
	
	public function findAnonymous()
	{
		$user = new EmCore_Model_UserItem();
		$user->id = EmCore_Model_User::USER_ANONYMOUS;
		$user->setGroups(array(new EmCore_Model_GroupItem(array('id' => EmCore_Model_Group::GROUP_ANONYMOUS))));

		return $user;
	}
	
	
	public function getGroupsFor(EmCore_Model_UserItem $user)
	{
		$tbl = new EmCore_Model_DbTable_Ugroup();
		$sel = $tbl->getAdapter()->select()->from("emerald_ugroup", "*");
		$sel->join('emerald_user_ugroup', "emerald_ugroup.id = emerald_user_ugroup.ugroup_id AND emerald_user_ugroup.user_id = {$user->id}", null);
		
		
		$res = $tbl->getAdapter()->fetchAll($sel, null, Zend_Db::FETCH_ASSOC);
		
		$groups = array();
		foreach($res as $row) {
			$groups[] = new EmCore_Model_GroupItem($row);
		}
		
		return $groups;
		
		
	}
		
	
	public function save(EmCore_Model_UserItem $user)
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

	
	public function delete(EmCore_Model_UserItem $user)
	{
		$tbl = $this->getTable();
		$row = $tbl->find($user->id)->current();
		if(!$row) {
			throw new Emerald_Model_Exception('Could not delete');
		}
		
		$row->delete();
		
	}
	
	
	
	public function setPassword(EmCore_Model_UserItem $user, $password)
	{
								
		$password = md5($password);
		
		$user->passwd = $password;
		$this->save($user);
	}
	
	
	public function setGroups(EmCore_Model_UserItem $user, $groups)
	{
	
		$tbl = new EmCore_Model_DbTable_UserGroup();
					
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