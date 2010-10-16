<?php

namespace Emerald\Filelib\Publisher;

/**
 * Publisher interface
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
interface Publisher
{
    /**
     * Publishes a file
     * 
     * @param \Emerald\Filelib\File $file
     */
    public function publish(\Emerald\Filelib\File $file);
        
    /**
     * Publishes a version of a file
     * 
     * @param \Emerald\Filelib\File $file
     * @param \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version
     */
    public function publishVersion(\Emerald\Filelib\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version);
    
    /**
     * Unpublishes a file
     * 
     * @param \Emerald\Filelib\File $file
     */
    public function unpublish(\Emerald\Filelib\File $file);
    
    /**
     * Unpublishes a version of a file
     * 
     * @param \Emerald\Filelib\File $file
     * @param \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version
     */
    public function unpublishVersion(\Emerald\Filelib\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version);
        
    /**
     * Returns url to a file
     * 
     * @param \Emerald\Filelib\File $file
     * @return string
     */
    public function getUrl(\Emerald\Filelib\File $file);
    
    /**
     * Returns url to a version of a file
     * 
     * @param \Emerald\Filelib\File $file
     * @param \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version
     * @return string
     */
    public function getUrlVersion(\Emerald\Filelib\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version);
    
}