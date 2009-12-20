<?php
class Core_Model_GroupItem extends Emerald_Model_AbstractItem implements Emerald_Acl_Role_Interface
{
	const GROUP_ANONYMOUS = 1;
	const GROUP_ROOT = 2;
	
	
	public function getRoleId()
	{
		return 'Emerald_Group_' . $this->id;
	}
		
	
	public function __lazyLoadAclRole(Zend_Acl $acl)
	{
		$acl->addRole($this);
	}
	
} 