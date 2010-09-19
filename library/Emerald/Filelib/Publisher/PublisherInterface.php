<?php
/**
 * Publisher interface
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
interface Emerald_Filelib_Publisher_PublisherInterface
{
    /**
     * Publishes a file
     * 
     * @param Emerald_Filelib_FileItem $file
     */
    public function publish(Emerald_Filelib_FileItem $file);
        
    /**
     * Publishes a version of a file
     * 
     * @param Emerald_Filelib_FileItem $file
     * @param Emerald_Filelib_Plugin_VersionProvider_Interface $version
     */
    public function publishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version);
    
    /**
     * Unpublishes a file
     * 
     * @param Emerald_Filelib_FileItem $file
     */
    public function unpublish(Emerald_Filelib_FileItem $file);
    
    /**
     * Unpublishes a version of a file
     * 
     * @param Emerald_Filelib_FileItem $file
     * @param Emerald_Filelib_Plugin_VersionProvider_Interface $version
     */
    public function unpublishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version);
    
}