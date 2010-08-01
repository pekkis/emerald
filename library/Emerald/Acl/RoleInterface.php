<?php
/**
 * Interface for autoloading Acl role
 * 
 * @package Emerald_Acl
 * @author pekkis
 * 
 */
interface Emerald_Acl_RoleInterface extends Zend_Acl_Role_Interface
{
    public function autoloadAclRole(Zend_Acl $acl);
}