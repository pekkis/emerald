<?php
/**
 * Interface for building an ACL implementation for Filelib
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
interface Emerald_Filelib_Acl_Interface
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