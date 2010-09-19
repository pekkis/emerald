<?php
/**
 * Publishes files in a filesystem by retrieving them from storage and creating a copy
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class Emerald_Filelib_Publisher_Filesystem_Copy extends Emerald_Filelib_Publisher_Filesystem implements Emerald_Filelib_Publisher_PublisherInterface
{
    
    public function publish(Emerald_Filelib_FileItem $file)
    {
        $fl = $this->getFilelib();
        $linker = $file->getProfileObject()->getLinker();
        
        $link = $this->getPublicRoot() . '/' . $linker->getLink($file, true);
        
        if(!is_file($link)) {

            $path = dirname($link);
            if(!is_dir($path)) {
                mkdir($path, $this->getDirectoryPermission(), true);
            }

            $tmp = $this->getFilelib()->getStorage()->retrieve($file);

            copy($tmp, $link);
            chmod($link, $this->getFilePermission());            
            
        }
    }
    
    public function publishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $fl = $this->getFilelib();
            
        $link = $this->getPublicRoot() . '/' . $file->getProfileObject()->getLinker()->getLinkVersion($file, $version);
        
        if(!is_file($link)) {

            $path = dirname($link);
            if(!is_dir($path)) {
                mkdir($path, $this->getDirectoryPermission(), true);
            }
            
            $tmp = $this->getFilelib()->getStorage()->retrieveVersion($file, $version);
            copy($tmp, $link);
            chmod($link, $this->getFilePermission());            
        }
    }
    
    public function unpublish(Emerald_Filelib_FileItem $file)
    {
        $link = $this->getPublicRoot() . '/' . $file->getProfileObject()->getLinker()->getLink($file);
        if(is_file($link)) {
            unlink($link);
        }
    }
    
    public function unpublishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $link = $this->getPublicRoot() . '/' . $file->getProfileObject()->getLinker()->getLinkVersion($file, $version);
        if(is_file($link)) {
            unlink($link);
        }
    }
    
}