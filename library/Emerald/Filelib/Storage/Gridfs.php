<?php
class Emerald_Filelib_Storage_Gridfs extends Emerald_Filelib_Storage_Abstract implements Emerald_Filelib_Storage_StorageInterface
{
    private $_mongo;
    
    private $_collection;
    
    private $_prefix;
    
    private $_gridFs;
    
    public function setMongo(MongoDB $mongo)
    {
        $this->_mongo = $mongo;
    }
        
    
    public function getMongo()
    {
        return $this->_mongo;
    }
    
    
    public function getGridFS()
    {
        if(!$this->_gridFs) {
            $this->_gridFs = $this->getMongo()->getGridFS($this->getPrefix());    
        }
        return $this->_gridFs;
    }
    
    
    
    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
    }
    
    
    public function getPrefix()
    {
        return $this->_prefix;
    }
    
    
    
    public function store(Emerald_Filelib_FileUpload $upload, Emerald_Filelib_FileItem $file)
    {
        $filename = $file->getProfileObject()->getSymlinker()->getLink($file);
        $this->getGridFS()->storeFile($upload->getPathname(), array('filename' => $filename, 'metadata' => array('id' => $file->id, 'version' => 'original', 'mimetype' => $file->mimetype) ));
    }
    
    public function storeVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version, $tempFile)
    {
        $filename = $file->getProfileObject()->getSymlinker()->getLinkVersion($file, $version);
        $this->getGridFS()->storeFile($tempFile, array('filename' => $filename, 'metadata' => array('id' => $file->id, 'version' => $version->getIdentifier(), 'mimetype' => $file->mimetype) ));
    }
    
    public function publish(Emerald_Filelib_FileItem $file)
    {
        
    }
    
    public function publishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        
    }
    
    public function unpublish(Emerald_Filelib_FileItem $file)
    {
        
    }
    
    public function unpublishVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        
    }
    
    public function retrieve(Emerald_Filelib_FileItem $file)
    {
        $filename = $file->getProfileObject()->getSymlinker()->getLink($file);
        $file = $this->getGridFS()->findOne(array('filename' => $filename));
        return $this->_toTemp($file);
        
    }
    
    public function retrieveVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $filename = $file->getProfileObject()->getSymlinker()->getLinkVersion($file, $version);
        $file = $this->getGridFS()->findOne(array('filename' => $filename));
        return $this->_toTemp($file);
    }
    
    public function delete(Emerald_Filelib_FileItem $file)
    {
        $filename = $file->getProfileObject()->getSymlinker()->getLink($file);
        $this->getGridFS()->remove(array('filename' => $filename));
    }
    
    public function deleteVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version)
    {
        $filename = $file->getProfileObject()->getSymlinker()->getLinkVersion($file, $version);
        $this->getGridFS()->remove(array('filename' => $filename));
    }
    
    
    
    private function _toTemp(MongoGridFSFile $file)
    {
        $tmp = $this->getFilelib()->getTempDir() . '/' . tmpfile();
        $file->write($tmp);
        
        return new Emerald_FileObject($tmp);
        
        
    }
    
    
    
    
    
    
    
    
    
}