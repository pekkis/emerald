<?php

namespace Emerald\Filelib\Publisher;

/**
 * Publisher interface
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
interface PublisherInterface
{
    /**
     * Publishes a file
     * 
     * @param \Emerald\Filelib\FileItem $file
     */
    public function publish(\Emerald\Filelib\FileItem $file);
        
    /**
     * Publishes a version of a file
     * 
     * @param \Emerald\Filelib\FileItem $file
     * @param \Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version
     */
    public function publishVersion(\Emerald\Filelib\FileItem $file, \Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version);
    
    /**
     * Unpublishes a file
     * 
     * @param \Emerald\Filelib\FileItem $file
     */
    public function unpublish(\Emerald\Filelib\FileItem $file);
    
    /**
     * Unpublishes a version of a file
     * 
     * @param \Emerald\Filelib\FileItem $file
     * @param \Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version
     */
    public function unpublishVersion(\Emerald\Filelib\FileItem $file, \Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version);
    
}