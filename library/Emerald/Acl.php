<?php
/**
 * Emerald Acl helper class... temporarily here.
 * 
 * @TODO: OPTIMIZE!
 * @TODO: CACHETIZE!
 * @TODO: TERRORIZE!
 *
 */
class Emerald_Acl extends Zend_Acl
{
	
	static public function initialize(Zend_Acl $acl, Emerald_Application_Customer $customer)
	{
        
        
        $anonGroup = 'Emerald_Group_' . Core_Model_GroupItem::GROUP_ANONYMOUS;
        $acl->addRole($anonGroup);
        $acl->deny($anonGroup);

        $rootGroup = 'Emerald_Group_' . Core_Model_GroupItem::GROUP_ROOT; 
        $acl->addRole($rootGroup);
        $acl->allow($rootGroup);
        
        return $acl;
		
	}
	
	
	
	public function isAllowed($role = null, $resource = null, $privilege = null)
	{
		if(!$this->hasRole($role)) {
			$role->__lazyLoadAclRole($this);
		}
		
		if(!$this->has($resource)) {
			$resource->__lazyLoadAclResource($this);
		}

		
		return parent::isAllowed($role, $resource, $privilege);
		
	}
	
	
	
	
	
	
	
}

?>