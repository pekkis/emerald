<?php
class Emerald_Filelib_Storage_Amazon_S3Storage extends Emerald_Filelib_Storage_AbstractStorage implements Emerald_Filelib_Storage_StorageInterface
{
    
    
    private $_storage;
        
    private $_bucket;
    
    private $_key;
    
    private $_secretKey;
    
    /**
     * @var array Registered temporary files
     */
    private $_tempFiles = array();
    
    /**
     * Deletes all temp files on destruct
     */
    public function __destruct()
    {
        foreach($this->_tempFiles as $tempFile) {
            unlink($tempFile->getPathname());
        }
    }
    
    /**
     * @return Zend_Service_Amazon_S3
     */
    public function getStorage()
    {
        if(!$this->_storage) {
            $this->_storage = new Zend_Service_Amazon_S3($this->getKey(), $this->getSecretKey());
        }
        
        if(!$this->_storage->isBucketAvailable($this->getBucket())) {
            $this->_storage->createBucket($this->getBucket());
        }
        
        return $this->_storage;
        
    }
    
    
    public function setBucket($bucket)
    {
        $this->_bucket = $bucket;
    }
    
    
    public function getBucket()
    {
        return $this->_bucket;
    }
    
    
    
    public function setKey($key)
    {
        $this->_key = $key;
    }
    
    
    public function getKey()
    {
        return $this->_key;
    }
    
    
    public function setSecretKey($secretKey)
    {
        $this->_secretKey = $secretKey;
    }
        
    public function getSecretKey()
    {
        return $this->_secretKey;
    }

    
    private function _getPath($file)
    {
        return $this->getBucket() . '/' . $file->id;
    }
        
    /**
     * Stores an uploaded file
     * 
     * @param Emerald\Filelib\FileUpload $upload
     * @param Emerald\Filelib\FileItem $file
     */
    public function store(Emerald\Filelib\FileUpload $upload, Emerald\Filelib\FileItem $file)
    {
        $object = $this->_getPath($file);
        $this->getStorage()->putFile($upload->getPathname(), $object);
    }
    
    /**
     * Stores a version of a file
     * 
     * @param Emerald\Filelib\FileItem $file
     * @param Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version
     * @param unknown_type $tempFile File to be stored
     */
    public function storeVersion(Emerald\Filelib\FileItem $file, Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version, $tempFile)
    {
        $object = $this->_getPath($file) . '_' . $version->getIdentifier();
        $this->getStorage()->putFile($tempFile, $object);
    }
    
    /**
     * Retrieves a file and temporarily stores it somewhere so it can be read.
     * 
     * @param Emerald\Filelib\FileItem $file
     * @return Emerald\Base\Spl\FileObject
     */
    public function retrieve(Emerald\Filelib\FileItem $file)
    {
        $object = $this->_getPath($file);
        $ret = $this->getStorage()->getObject($object);
        return $this->_toTemp($ret);        
    }
    
    /**
     * Retrieves a version of a file and temporarily stores it somewhere so it can be read.
     * 
     * @param Emerald\Filelib\FileItem $file
     * @param Emerald_Filelib_VersionProvider_Interface $version
     * @return Emerald\Base\Spl\FileObject
     */
    public function retrieveVersion(Emerald\Filelib\FileItem $file, Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version)
    {
        $object = $this->_getPath($file) . '_' . $version->getIdentifier();
        $ret = $this->getStorage()->getObject($object);
        return $this->_toTemp($ret);        
    }
    
    /**
     * Deletes a file
     * 
     * @param Emerald\Filelib\FileItem $file
     */
    public function delete(Emerald\Filelib\FileItem $file)
    {
        $object = $this->_getPath($file);
        $this->getStorage()->removeObject($object);
    }
    
    /**
     * Deletes a version of a file
     * 
     * @param Emerald\Filelib\FileItem $file
     * @param Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version
     */
    public function deleteVersion(Emerald\Filelib\FileItem $file, Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version)
    {
        $object = $this->_getPath($file) . '_' . $version->getIdentifier();
        $this->getStorage()->removeObject($object);
    }
    
    
        /**
     * Writes a mongo file to temporary file and registers it as an internal temp file
     * 
     * @param MongoGridFSFile $file
     * @return Emerald\Base\Spl\FileObject
     */
    private function _toTemp($file)
    {
        $tmp = $this->getFilelib()->getTempDir() . '/' . tmpfile();
        
        file_put_contents($tmp, $file);
        
        $fo = new Emerald\Base\Spl\FileObject($tmp);
        
        $this->_registerTempFile($fo);
        
        return $fo;
        
    }
    
    /**
     * Registers an internal temp file
     * 
     * @param Emerald\Base\Spl\FileObject $fo
     */
    private function _registerTempFile(Emerald\Base\Spl\FileObject $fo)
    {
        $this->_tempFiles[] = $fo;
    }
    
    
    
    
}