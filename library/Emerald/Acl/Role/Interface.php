<?php 
interface Emerald_Acl_Role_Interface extends Zend_Acl_Role_Interface
{
	public function __lazyLoadAclRole(Zend_Acl $acl);
	
}