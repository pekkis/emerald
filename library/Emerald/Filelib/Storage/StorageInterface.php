<?php
/**
 * Storage interface
 * 
 * @author pekkis
 * @package Emerald_Filelib
 * @todo Something is not perfect yet... Rethink and finalize
 *
 */
interface Emerald_Filelib_Storage_StorageInterface
{
    /**
     * Sets filelib
     *
     * @return Emerald_Filelib_FileLibrary Filelib
     */
    public function setFilelib(Emerald_Filelib_FileLibrary $filelib);

    /**
     * Returns filelib
     *
     * @return Emerald_Filelib_FileLibrary Filelib
     */
    public function getFilelib();
    
    /**
     * Stores an uploaded file
     * 
     * @param Emerald_Filelib_FileUpload $upload
     * @param Emerald_Filelib_FileItem $file
     */
    public function store(Emerald_Filelib_FileUpload $upload, Emerald_Filelib_FileItem $file);
    
    /**
     * Stores a version of a file
     * 
     * @param Emerald_Filelib_FileItem $file
     * @param Emerald_Filelib_Plugin_VersionProvider_Interface $version
     * @param unknown_type $tempFile File to be stored
     */
    public function storeVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version, $tempFile);
    
    /**
     * Retrieves a file and temporarily stores it somewhere so it can be read.
     * 
     * @param Emerald_Filelib_FileItem $file
     * @return Emerald_Base_Spl_FileObject
     */
    public function retrieve(Emerald_Filelib_FileItem $file);
    
    /**
     * Retrieves a version of a file and temporarily stores it somewhere so it can be read.
     * 
     * @param Emerald_Filelib_FileItem $file
     * @param Emerald_Filelib_VersionProvider_Interface $version
     * @return Emerald_Base_Spl_FileObject
     */
    public function retrieveVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version);
    
    /**
     * Deletes a file
     * 
     * @param Emerald_Filelib_FileItem $file
     */
    public function delete(Emerald_Filelib_FileItem $file);
    
    /**
     * Deletes a version of a file
     * 
     * @param Emerald_Filelib_FileItem $file
     * @param Emerald_Filelib_Plugin_VersionProvider_Interface $version
     */
    public function deleteVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version);
    
}