<?php
class Core_Model_GroupItem extends Emerald_Model_AbstractItem implements Emerald_Acl_Role_Interface
{
	
	public function getRoleId()
	{
		return 'Emerald_Group_' . $this->id;
	}
		
	
	public function __lazyLoadAclRole(Zend_Acl $acl)
	{
		$acl->addRole($this);
	}
	
} 