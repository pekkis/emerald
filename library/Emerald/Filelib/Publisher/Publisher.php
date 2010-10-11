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
     * @param \Emerald\Filelib\FileItem $file
     */
    public function publish(\Emerald\Filelib\FileItem $file);
        
    /**
     * Publishes a version of a file
     * 
     * @param \Emerald\Filelib\FileItem $file
     * @param \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version
     */
    public function publishVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version);
    
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
     * @param \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version
     */
    public function unpublishVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version);
        
    /**
     * Returns url to a file
     * 
     * @param \Emerald\Filelib\FileItem $file
     * @return string
     */
    public function getUrl(\Emerald\Filelib\FileItem $file);
    
    /**
     * Returns url to a version of a file
     * 
     * @param \Emerald\Filelib\FileItem $file
     * @param \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version
     * @return string
     */
    public function getUrlVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version);
    
}