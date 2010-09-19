<?php
/**
 * Does absolutely nothing when files are published. Surprisingly it always succeeds.
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class Emerald_Filelib_Publisher_Nothing
{
    
    public function publish(Emerald_Filelib_FileItem $file)
    {
        
    }
        
    public function publishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        
    }
    
    public function unpublish(Emerald_Filelib_FileItem $file)
    {
        
    }
    
    public function unpublishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        
    }
    
}