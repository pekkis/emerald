<?php

namespace Emerald\Filelib\Storage;

/**
 * Stores files in MongoDB's GridFS filesystem
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class GridfsStorage extends \Emerald\Filelib\Storage\AbstractStorage implements \Emerald\Filelib\Storage\Storage
{
    /**
     * @var \MongoDB Mongo reference
     */
    private $_mongo;
    
    /**
     * @var string Collection name
     */
    private $_collection;
    
    /**
     * @var string GridFS prefix
     */
    private $_prefix;
    
    /**
     * @var \MongoGridFS GridFS reference
     */
    private $_gridFs;
    
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
     * Sets mongo
     * 
     * @param \MongoDB $mongo
     */
    public function setMongo(\MongoDB $mongo)
    {
        $this->_mongo = $mongo;
    }
    
    /**
     * Returns mongo
     * 
     * @return \MongoDB
     */
    public function getMongo()
    {
        return $this->_mongo;
    }
    
    /**
     * Returns GridFS
     * 
     * @return \MongoGridFS
     */
    public function getGridFS()
    {
        if(!$this->_gridFs) {
            $this->_gridFs = $this->getMongo()->getGridFS($this->getPrefix());    
        }
        return $this->_gridFs;
    }
    
    /**
     * Sets gridfs prefix
     * 
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
    }
    
    /**
     * Returns gridfs prefix
     * 
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }
    
    /**
     * Writes a mongo file to temporary file and registers it as an internal temp file
     * 
     * @param \MongoGridFSFile $file
     * @return \Emerald\Base\Spl\Fileobject 
     * 
     */
    private function _toTemp(\MongoGridFSFile $file)
    {
        $tmp = $this->getFilelib()->getTempDir() . '/' . tmpfile();
        $file->write($tmp);
        
        $fo = new \Emerald\Base\Spl\FileObject($tmp);
        
        $this->_registerTempFile($fo);
        
        return $fo;
        
    }
    
    /**
     * Registers an internal temp file
     * 
     * @param \Emerald\Base\Spl\FileObject $fo
     */
    private function _registerTempFile(\Emerald\Base\Spl\FileObject $fo)
    {
        $this->_tempFiles[] = $fo;
    }
    
    public function store(\Emerald\Filelib\File\FileUpload $upload, \Emerald\Filelib\File\File $file)
    {
        $filename = $this->_getFilename($file);
        
        $this->getGridFS()->storeFile($upload->getPathname(), array('filename' => $filename, 'metadata' => array('id' => $file->getId(), 'version' => 'original', 'mimetype' => $file->getMimetype()) ));
    }
    
    public function storeVersion(\Emerald\Filelib\File\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version, $tempFile)
    {
        $filename = $this->_getFilenameVersion($file, $version);
        
        $this->getGridFS()->storeFile($tempFile, array('filename' => $filename, 'metadata' => array('id' => $file->getId(), 'version' => $version->getIdentifier(), 'mimetype' => $file->getMimetype()) ));
    }
    
    public function retrieve(\Emerald\Filelib\File\File $file)
    {
        $filename = $this->_getFilename($file);
        
        $file = $this->getGridFS()->findOne(array('filename' => $filename));

        if(!$file) {
            throw new \Emerald\Filelib\FilelibException("Filename '{$filename}' not retrievable");
        }
        
        
        return $this->_toTemp($file);
    }
    
    public function retrieveVersion(\Emerald\Filelib\File\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version)
    {
        $filename = $this->_getFilenameVersion($file, $version);
        
        $file = $this->getGridFS()->findOne(array('filename' => $filename));
        
        if(!$file) {
            throw new \Emerald\Filelib\FilelibException("Filename '{$filename}' not retrievable");
        }
        
        
        return $this->_toTemp($file);
    }
    
    public function delete(\Emerald\Filelib\File\File $file)
    {
        $filename = $this->_getFilename($file);
        
        $this->getGridFS()->remove(array('filename' => $filename));
    }
    
    public function deleteVersion(\Emerald\Filelib\File\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version)
    {
        $filename = $this->_getFilenameVersion($file, $version);
        
        $this->getGridFS()->remove(array('filename' => $filename));
    }
    
    
    private function _getFilename(\Emerald\Filelib\File\File $file)
    {
        return $file->getFolderId() . '/' . $file->getId();
    }
    
    private function _getFilenameVersion(\Emerald\Filelib\File\File $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version)
    {
        return $file->getFolderId() . '/' . $file->getId() . '/' . $version->getIdentifier();
    }
    
    
}