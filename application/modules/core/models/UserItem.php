<?php
class Core_Model_UserItem extends Emerald_Model_AbstractItem implements Emerald_Acl_Role_Interface
{
	
	
	
	protected $_groups;
	
	
	public function getGroups()
	{
		if(!$this->_groups) {
			$model = new Core_Model_User();
			$this->_groups = $model->getGroupsFor($this); 			
		}
		return $this->_groups;
		
	}
	
	
	public function setGroups(array $groups)
	{
		$this->_groups = $groups;
	}
	
	
	public function __lazyLoadAclRole(Zend_Acl $acl)
	{
		if(!$acl->hasRole($this)) {
			$gruppen = array();
			$groupz = $this->getGroups();
			foreach($groupz as $group) {
	       		$gruppen[] = $group;
	       	}
	       	$acl->addRole($this, $gruppen);
		}
	}
	
	
	public function getRoleId()
	{
		return 'Emerald_User_' . $this->id;
	}
	
	
	
	public function inGroup($groupId)
	{
		foreach($this->getGroups() as $group) {
			if($group->id == $groupId) {
				return true;
			}
		}
		return false; 
	}
	
	
}