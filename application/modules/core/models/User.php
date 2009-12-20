<?php
class Core_Model_User
{
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
		$user->id = Core_Model_UserItem::USER_ANONYMOUS;
		$user->setGroups(array(new Core_Model_GroupItem(array('id' => Core_Model_GroupItem::GROUP_ANONYMOUS))));

		return $user;
	}
	
	
	
}