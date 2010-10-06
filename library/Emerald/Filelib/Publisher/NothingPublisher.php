<?php
/**
 * Does absolutely nothing when files are published. Surprisingly it always succeeds.
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class Emerald_Filelib_Publisher_NothingPublisher extends Emerald_Filelib_Publisher_AbstractPublisher implements Emerald_Filelib_Publisher_PublisherInterface
{
    
    public function publish(Emerald\Filelib\FileItem $file)
    {
        
    }
        
    public function publishVersion(Emerald\Filelib\FileItem $file, Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version)
    {
        
    }
    
    public function unpublish(Emerald\Filelib\FileItem $file)
    {
        
    }
    
    public function unpublishVersion(Emerald\Filelib\FileItem $file, Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version)
    {
        
    }
    
}