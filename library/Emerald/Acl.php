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

		
        $anonGroup = 'Emerald_Group_' . Core_Model_Group::GROUP_ANONYMOUS;
        $acl->addRole($anonGroup);
        $acl->deny($anonGroup);

        $rootGroup = 'Emerald_Group_' . Core_Model_Group::GROUP_ROOT; 
        $acl->addRole($rootGroup);
        $acl->allow($rootGroup);

        $acl->addResource('locale');
        $acl->allow($anonGroup, 'locale');
        
        
        return $acl;
		
	}
	
	
	
	public function isAllowed($role = null, $resource = null, $privilege = null)
	{
		if(!$this->hasRole($role)) {
			
			if(!$role instanceof Emerald_Acl_Role_Interface) {
				throw new Zend_Acl_Exception("Can not autoload role '{$role}'");
			}
			$role->__lazyLoadAclRole($this);
			$this->cacheSave();
		}
		
		if(!$this->has($resource)) {
			
			if(!$resource instanceof Emerald_Acl_Resource_Interface) {
				
				if(preg_match("/^Emerald_Page/", $resource)) {
					
					$split = explode("_", $resource);
					$pageModel = new Core_Model_Page();
					$resource = $pageModel->find($split[2]);
				} else {
					throw new Zend_Acl_Exception("Can not autoload resource '{$resource}'");	
				}
				
			}
						
			$resource->__lazyLoadAclResource($this);
			$this->cacheSave();
		}

		
		return parent::isAllowed($role, $resource, $privilege);
		
	}
	
	
	public function remove($resource)
	{
		parent::remove($resource);
		$this->cacheSave();
	}
	
	
	
	
	public function cacheSave()
	{
		Zend_Registry::get('Emerald_CacheManager')->getCache('global')->save($this, 'acl');	
	}
	
	
	
	public function cacheRemove()
	{
		Zend_Registry::get('Emerald_CacheManager')->getCache('global')->remove('acl');
	}
	
	
	
	
	
	
}

?>