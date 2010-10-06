<?php

namespace Emerald\Filelib\Acl;

/**
 * Interface for building an ACL implementation for Filelib
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
interface AclInterface
{

    /**
     * Returns whether a resource is readable
     * 
     * @param mixed $resource
     */
    public function isReadable($resource);

    /**
     * Returns whether a resource is writeable
     * 
     * @param mixed $resource
     */
    public function isWriteable($resource);

    /**
     * Returns whether a resource is readable by anonymous users
     * 
     * @param mixed $resource
     */
    public function isAnonymousReadable($resource);


}