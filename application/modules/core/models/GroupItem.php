<?php
class Core_Model_GroupItem extends Emerald_Model_AbstractItem implements Emerald_Acl_Role_Interface
{
	private $_users;
	
	public function getRoleId()
	{
		return 'Emerald_Group_' . $this->id;
	}
		
	
	public function __lazyLoadAclRole(Zend_Acl $acl)
	{
		$acl->addRole($this);
	}
	
	
	public function getUsers()
	{
		if(!$this->_users) {
			$model = new Core_Model_Group();
			$this->_users = $model->getUsersIn($this); 			
		}
		return $this->_users;
		
	}
	
	
} 