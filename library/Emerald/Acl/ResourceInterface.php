<?php
/**
 * Interface for autoloading Acl resource
 * 
 * @package Emerald_Acl
 * @author pekkis
 * 
 */
interface Emerald_Acl_ResourceInterface extends Zend_Acl_Resource_Interface
{
    public function autoloadAclResource(Zend_Acl $acl);
}