<?php
class Emerald_Group extends Zend_Db_Table_Row_Abstract implements Zend_Acl_Role_Interface 
{
	const GROUP_ANONYMOUS = 1;
	const GROUP_ROOT = 2;
	
	
	
	public function init()
	{
		$acl = Zend_Registry::get('Emerald_Acl');
				
		// If group is not the anonymous/root group, it inherits all the anonymous' rights.
		if(!$acl->hasRole($this)) {
			$acl->addRole($this, ($this->id != self::GROUP_ANONYMOUS && $this->id != self::GROUP_ROOT) ? 'Emerald_Group_1' : null); 
		}
		
	}
		
	
	public function getRoleId()
	{
		return 'Emerald_Group_' . $this->id;
	}
	
}