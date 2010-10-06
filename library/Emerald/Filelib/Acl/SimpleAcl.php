<?php

namespace Emerald\Filelib\Acl;

/**
 * Simple ACL allows everything to everyone
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class SimpleAcl implements Acl
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