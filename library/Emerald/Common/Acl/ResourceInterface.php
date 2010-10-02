<?php
/**
 * Interface for an autoloading Acl resource
 * 
 * @author pekkis
 * 
 */
interface Emerald_Common_Acl_ResourceInterface extends Zend_Acl_Resource_Interface
{
    public function autoloadAclResource(Zend_Acl $acl);
}