<?php

namespace Emerald\Filelib\Storage;

/**
 * Storage interface
 * 
 * @author pekkis
 * @package Emerald_Filelib
 * @todo Something is not perfect yet... Rethink and finalize
 *
 */
interface Storage
{
    /**
     * Sets filelib
     *
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function setFilelib(\Emerald\Filelib\FileLibrary $filelib);

    /**
     * Returns filelib
     *
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function getFilelib();
    
    /**
     * Stores an uploaded file
     * 
     * @param \Emerald\Filelib\FileUpload $upload
     * @param \Emerald\Filelib\File $file
     */
    public function store(\Emerald\Filelib\FileUpload $upload, \Emerald\Filelib\File $file);
    
    /**
     * Stores a version of a file
     * 
     * @param \Emerald\Filelib\File $file
     * @param \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version
     * @param unknown_type $tempFile File to be stored
     */
    public function storeVersion(\Emerald\Filelib\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version, $tempFile);
    
    /**
     * Retrieves a file and temporarily stores it somewhere so it can be read.
     * 
     * @param \Emerald\Filelib\File $file
     * @return \Emerald\Base\Spl\FileObject
     */
    public function retrieve(\Emerald\Filelib\File $file);
    
    /**
     * Retrieves a version of a file and temporarily stores it somewhere so it can be read.
     * 
     * @param \Emerald\Filelib\File $file
     * @param \Emerald_Filelib_VersionProvider_Interface $version
     * @return \Emerald\Base\Spl\FileObject
     */
    public function retrieveVersion(\Emerald\Filelib\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version);
    
    /**
     * Deletes a file
     * 
     * @param \Emerald\Filelib\File $file
     */
    public function delete(\Emerald\Filelib\File $file);
    
    /**
     * Deletes a version of a file
     * 
     * @param \Emerald\Filelib\File $file
     * @param \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version
     */
    public function deleteVersion(\Emerald\Filelib\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version);
    
}