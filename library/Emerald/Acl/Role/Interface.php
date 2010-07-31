<?php
interface Emerald_Acl_Role_Interface extends Zend_Acl_Role_Interface
{
    public function autoloadAclRole(Zend_Acl $acl);

}