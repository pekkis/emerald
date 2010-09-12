<?php
interface Emerald_Filelib_Publisher_PublisherInterface
{
    
    public function publish(Emerald_Filelib_FileItem $file);
        
    public function publishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version);
    
    public function unpublish(Emerald_Filelib_FileItem $file);
    
    public function unpublishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version);
    
    
}