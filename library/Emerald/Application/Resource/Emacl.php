<?php
class Emerald_Application_Resource_Emacl extends Zend_Application_Resource_ResourceAbstract
{

	public function init()
	{
		$options = $this->getOptions();
		
		$this->getBootstrap()->bootstrap('customer')->bootstrap('emdb')->bootstrap('emuser')->bootstrap('filelib');
		
		$customer = $this->getBootstrap()->getResource('customer');
		
		$cache = Zend_Registry::get('Emerald_CacheManager')->getCache('default');
		if(!$acl = $cache->load('acl')) {
			$acl = new Emerald_Acl(); 
			Emerald_Acl::initialize($acl, $customer);
			$cache->save($acl, 'acl'); 
		}

		if($this->_options['initFilelib'] == true) {
			$filelib = $this->getBootstrap()->getResource('filelib');
			$user = $this->getBootstrap()->getResource('emuser');
			$aclHandler = new Emerald_Filelib_Acl_Zend();
			$aclHandler->setAcl($acl);
			$aclHandler->setAnonymousRole("Emerald_Group_" . EmCore_Model_Group::GROUP_ANONYMOUS);
					
			$aclHandler->setRole($user);
			
			$filelib->setAcl($aclHandler);
			$filelib->setFileItemClass("EmCore_Model_FileItem");
			$filelib->setFolderItemClass("EmCore_Model_FolderItem");		
		}
		
		Zend_Registry::set('Emerald_Acl', $acl);
                                
        return $acl;
		
		
	}
	
}