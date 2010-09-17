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
     * @param unknown_type $resource
     */
    public function isReadable($resource);

    public function isWriteable($resource);

    public function isAnonymousReadable($resource);


}