<?php

namespace Emerald\Filelib\Publisher;

/**
 * Does absolutely nothing when files are published. Surprisingly it always succeeds and never returns anything
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class NothingPublisher extends AbstractPublisher
{
    
    public function publish(\Emerald\Filelib\FileItem $file)
    {
        
    }
        
    public function publishVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version)
    {
        
    }
    
    public function unpublish(\Emerald\Filelib\FileItem $file)
    {
        
    }
    
    public function unpublishVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version)
    {
        
    }
    
    
    public function getUrl(\Emerald\Filelib\FileItem $file)
    {
        return false;    
    }
    
    public function getUrlVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider $version)
    {
        return false;
    }
    
    
}