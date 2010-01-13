<?php
class Core_Model_FileItem extends Emerald_Filelib_FileItem implements Emerald_Acl_Resource_Interface
{

	
	public function getResourceId()
	{
		return "Emerald_Filelib_File_{$this->id}";
	} 
	
	
	
	public function __lazyLoadAclResource(Zend_Acl $acl)
	{
		if(!$acl->has($this)) {
			
			$folder = $this->findFolder();
			if(!$acl->has($folder)) {
				$folder->__lazyLoadAclResource($acl);
			}
			
			$acl->addResource($this, $folder);
		}		
		
	}
	

}