<?php
class Admin_Model_Activity
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
			$table = new Admin_Model_DbTable_Activity();
		}
		return $table;
	}

	/**
	 * Returns table
	 * 
	 * @return Zend_Db_Table_Abstract
	 */
	public function getPermissionTable()
	{
		static $table;
		if(!$table) {
			$table = new Admin_Model_DbTable_Permission_Activity_Ugroup();
		}
		return $table;
	}
	
	
	public function getActivities()
	{
		$res = $this->getTable()->fetchAll(array(), array('category ASC', 'name ASC'));
		
		$activities = array();
		foreach($res as $row) {
			$activities[] = new Admin_Model_ActivityItem($row->toArray());
		}
		
		return new ArrayIterator($activities);
	}
	
	
	
	public function getActivitiesByCategory()
	{
		$cats = array();
		foreach($this->getActivities() as $activity) {
			$cats[$activity->category][] = $activity;
		}
		return $cats;		
		
	}
	
	
	public function getPermissions(Admin_Model_ActivityItem $activity)
	{
		$acl = Zend_Registry::get('Emerald_Acl');
		
		$groupModel = new Core_Model_Group();
		$groups = $groupModel->findAll();
		foreach($groups as $group) {
			if($acl->isAllowed($group, $activity)) {
				$perms[] = $group->id;
			}	
		}
		return $perms;
		
	}
	
	
	public function updatePermissions($activityPermissions)
	{
		$tbl = $this->getPermissionTable();
		
		foreach($activityPermissions as $id => $groups) {
			
			$tbl->delete($tbl->getAdapter()->quoteInto("activity_id = ?", $id));
			
			foreach($groups as $groupId) {
				$tbl->insert(array('activity_id' => $id, 'ugroup_id' => $groupId));
			}
			
		}
		
		
		
	}
	
	
	public function findByCategoryAndName($category, $name)
	{
		$res = $this->getTable()->fetchRow(array('category = ?' => $category, "name = ?" => $name));
		
		return ($res) ? new Admin_Model_ActivityItem($res->toArray()) : false;
	}
	
	
	
}
