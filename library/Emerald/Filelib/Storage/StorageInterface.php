<?php
interface Emerald_Filelib_Storage_StorageInterface
{
    /**
     * Sets filelib
     *
     * @return Emerald_Filelib Filelib
     */
    public function setFilelib(Emerald_Filelib $filelib);

    /**
     * Returns filelib
     *
     * @return Emerald_Filelib Filelib
     */
    public function getFilelib();
    
    public function store(Emerald_Filelib_FileUpload $upload, Emerald_Filelib_FileItem $file);
    
    public function storeVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version, $tempFile);
    
    public function retrieve(Emerald_Filelib_FileItem $file);
    
    public function retrieveVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version);
    
    public function delete(Emerald_Filelib_FileItem $file);
    
    public function deleteVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version);
    
    
}