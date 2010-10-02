<?php
/**
 * Interface for an autoloading Acl role
 * 
 * @package Emerald_Acl
 * @author pekkis
 * 
 */
interface Emerald_Common_Acl_RoleInterface extends Zend_Acl_Role_Interface
{
    public function autoloadAclRole(Zend_Acl $acl);
}