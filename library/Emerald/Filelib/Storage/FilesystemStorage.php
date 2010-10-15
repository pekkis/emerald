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
     * @var integer Files per directory
     */
    private $_filesPerDirectory = 500;

    /**
     * @var integer Levels in directory structure
     */
    private $_directoryLevels = 1;

    /**
     * @var integer Octal representation for directory permissions
     */
    private $_directoryPermission = 0700;

    /**
     * @var integer Octal representation for file permissions
     */
    private $_filePermission = 0600;

    /**
     * Sets files per directory
     *
     * @param integer $filesPerDirectory
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setFilesPerDirectory($filesPerDirectory)
    {
        $this->_filesPerDirectory = $filesPerDirectory;
        return $this;
    }

    /**
     * Returns files per directory
     *
     * @return integer
     */
    public function getFilesPerDirectory()
    {
        return $this->_filesPerDirectory;
    }

    /**
     * Sets levels per directory hierarchy
     *
     * @param integer $directoryLevels
     * @return \Emerald\Filelib\FileLibrary
     */
    public function setDirectoryLevels($directoryLevels)
    {
        $this->_directoryLevels = $directoryLevels;
        return $this;
    }

    /**
     * Returns levels in directory hierarchy
     *
     * @return integer
     */
    public function getDirectoryLevels()
    {
        return $this->_directoryLevels;
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
     * Returns directory identifier (path) for specified file id
     *
     * @param integer $fileId File id
     * @return string
     */
    public function getDirectoryId($fileId)
    {
        $directoryLevels = $this->getDirectoryLevels() + 1;
        $filesPerDirectory = $this->getFilesPerDirectory();

        if($directoryLevels < 1) {
            throw new \Emerald\Filelib\FilelibException("Invalid number of directory levels ('{$directoryLevels}')");
        }

        $arr = array();
        $tmpfileid = $fileId - 1;

        for($count = 1; $count <= $directoryLevels; ++$count) {
            $lus = $tmpfileid / pow($filesPerDirectory, $directoryLevels - $count);
            $tmpfileid = $tmpfileid % pow($filesPerDirectory, $directoryLevels - $count);
            $arr[] = floor($lus) + 1;
        }

        $puuppa = array_pop($arr);
        return implode(DIRECTORY_SEPARATOR, $arr);
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
        $dir = $root . '/' . $this->getDirectoryId($file->getId());

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
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file->getId()) . '/' . $version->getIdentifier();
                 
        if(!is_dir($path)) {
            mkdir($path, $this->getDirectoryPermission(), true);
        }
                 
        copy($tempFile, $path . '/' . $file->getId());
    }
    
    public function retrieve(\Emerald\Filelib\FileItem $file)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file->getId()) . '/' . $file->getId();
        
        if(!is_file($path)) {
            throw new \Emerald\Filelib\FilelibException('Could not retrieve file');
        }
        
        return new \Emerald\Base\Spl\FileObject($path);
    }
    
    public function retrieveVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file->getId()) . '/' . $version->getIdentifier() . '/' . $file->getId();
        
        if(!is_file($path)) {
            throw new \Emerald\Filelib\FilelibException('Could not retrieve file');
        }
        
        return new \Emerald\Base\Spl\FileObject($path);
    }
    
    public function delete(\Emerald\Filelib\FileItem $file)
    {
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file->getId()) . '/' . $file->getId();
            
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
        $path = $this->getRoot() . '/' . $this->getDirectoryId($file->getId()) . '/' . $version->getIdentifier() . '/' . $file->getId();
        unlink($path);
    }
}