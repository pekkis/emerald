<?php

namespace Emerald\Filelib\Storage;

/**
 * Stores files in a filesystem
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class FilesystemStorage extends \Emerald\Filelib\Storage\AbstractStorage implements \Emerald\Filelib\Storage\Storage
{
    /**
     * @var string Physical root
     */
    private $_root;

    /**
     * @var integer Octal representation for directory permissions
     */
    private $_directoryPermission = 0700;

    /**
     * @var integer Octal representation for file permissions
     */
    private $_filePermission = 0600;
    
    /**
     * @var \Emerald\Filelib\Storage\Filesystem\DirectoryIdCalculator\DirectoryIdCalculator
     */
    private $_directoryIdCalculator;
    
    
    public function __construct($options = array())
    {
        if(isset($options['directoryIdCalculator'])) {
            $d = $options['directoryIdCalculator'];
            unset($options['directoryIdCalculator']);

            $calculator = new $d['type']($d['options']);

            $this->setDirectoryIdCalculator($calculator);
        }
        
        \Emerald\Base\Options::setConstructorOptions($this, $options);
    }
    
    
    /**
     * Sets directory id calculator
     * 
     * @param Filesystem\DirectoryIdCalculator\DirectoryIdCalculator $directoryIdCalculator
     */
    public function setDirectoryIdCalculator(Filesystem\DirectoryIdCalculator\DirectoryIdCalculator $directoryIdCalculator)
    {
        $this->_directoryIdCalculator = $directoryIdCalculator;
    }
    
    
    /**
     * Returns directory id calculator
     * 
     * @return \Emerald\Filelib\Storage\Filesystem\DirectoryIdCalculator\DirectoryIdCalculator
     */
    public function getDirectoryIdCalculator()
    {
        return $this->_directoryIdCalculator;
    }
    
    
    public function getDirectoryId(\Emerald\Filelib\File $file)
    {
        return $this->getDirectoryIdCalculator()->calculateDirectoryId($file);
    }
    
    

    /**
     * Sets directory permission
     *
     * @param integer $directoryPermission
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function setDirectoryPermission($directoryPermission)
    {
        $this->_directoryPermission = octdec($directoryPermission);
        return $this;
    }

    /**
     * Returns directory permission
     *
     * @return integer
     */
    public function getDirectoryPermission()
    {
        return $this->_directoryPermission;
    }

    /**
     * Sets file permission
     *
     * @param integer $filePermission
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function setFilePermission($filePermission)
    {
        $this->_filePermission = octdec($filePermission);
        return $this;
    }

    /**
     * Returns file permission
     *
     * @return integer
     */
    public function getFilePermission()
    {
        return $this->_filePermission;
    }

    /**
     * Sets root
     *
     * @param string $root
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function setRoot($root)
    {
        $this->_root = $root;
    }

    /**
     * Returns root
     *
     * @return string
     */
    public function getRoot()
    {
        return $this->_root;
    }
    
    public function store(\Emerald\Filelib\FileUpload $upload, \Emerald\Filelib\FileItem $file)
    {
        $root = $this->getRoot();
        $dir = $root . '/' . $this->getDirectoryId($file);

        if(!is_dir($dir)) {
            @mkdir($dir, $this->getDirectoryPermission(), true);
        }

        if(!is_dir($dir) || !is_writable($dir)) {
            throw new \Emerald\Filelib\FilelibException('Could not write into directory', 500);
        }
            
        $fileTarget = $dir . '/' . $file->getId();

        copy($upload->getRealPath(), $fileTarget);
        chmod($fileTarget, $this->getFilePermission());
            
        if(!is_readable($fileTarget)) {
            throw new \Emerald\Filelib\FilelibException('Could not copy file to folder');
        }
    }
    
    public function storeVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version, $tempFile)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file) . '/' . $version->getIdentifier();
                 
        if(!is_dir($path)) {
            mkdir($path, $this->getDirectoryPermission(), true);
        }
                 
        copy($tempFile, $path . '/' . $file->getId());
    }
    
    public function retrieve(\Emerald\Filelib\FileItem $file)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file) . '/' . $file->getId();
        
        if(!is_file($path)) {
            throw new \Emerald\Filelib\FilelibException('Could not retrieve file');
        }
        
        return new \Emerald\Base\Spl\FileObject($path);
    }
    
    public function retrieveVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file) . '/' . $version->getIdentifier() . '/' . $file->getId();
        
        if(!is_file($path)) {
            throw new \Emerald\Filelib\FilelibException('Could not retrieve file');
        }
        
        return new \Emerald\Base\Spl\FileObject($path);
    }
    
    public function delete(\Emerald\Filelib\FileItem $file)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file) . '/' . $file->getId();
            
        $fileObj = new \SplFileObject($path);
        if(!$fileObj->isFile() || !$fileObj->isWritable()) {
            throw new \Emerald\Filelib\FilelibException('Can not delete file');
        }
        if(!@unlink($fileObj->getPathname())) {
            throw new \Emerald\Filelib\FilelibException('Can not delete file');
        }
    }
    
    
    public function deleteVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file) . '/' . $version->getIdentifier() . '/' . $file->getId();
        unlink($path);
    }
}