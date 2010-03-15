<?php 
interface Emerald_Acl_Resource_Interface extends Zend_Acl_Resource_Interface
{
	
	public function autoloadAclResource(Zend_Acl $acl);
	
	
}