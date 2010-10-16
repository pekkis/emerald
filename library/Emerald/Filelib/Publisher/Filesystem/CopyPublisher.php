<?php

namespace Emerald\Filelib\Publisher\Filesystem;

use Emerald\Filelib\Publisher\Publisher;

/**
 * Publishes files in a filesystem by retrieving them from storage and creating a copy
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class CopyPublisher extends AbstractFilesystemPublisher implements Publisher
{
    
    public function publish(\Emerald\Filelib\File $file)
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
    
    public function publishVersion(\Emerald\Filelib\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version)
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
    
    public function unpublish(\Emerald\Filelib\File $file)
    {
        $link = $this->getPublicRoot() . '/' . $file->getProfileObject()->getLinker()->getLink($file);
        if(is_file($link)) {
            unlink($link);
        }
    }
    
    public function unpublishVersion(\Emerald\Filelib\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version)
    {
        $link = $this->getPublicRoot() . '/' . $file->getProfileObject()->getLinker()->getLinkVersion($file, $version);
        if(is_file($link)) {
            unlink($link);
        }
    }
    
}