<?php
/**
 * Simple ACL allows everything to everyone
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class Emerald_Filelib_Acl_SimpleAcl implements Emerald_Filelib_Acl_AclInterface
{

    public function isReadable($resource)
    {
        return true;
    }


    public function isWriteable($resource)
    {
        return true;
    }


    public function isAnonymousReadable($resource)
    {
        return true;
    }





}