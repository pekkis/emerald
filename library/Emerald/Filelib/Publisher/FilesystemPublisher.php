<?php

namespace Emerald\Filelib\Publisher;

/**
 * Abstract filesystem publisher base class
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
abstract class FilesystemPublisher extends \Emerald\Filelib\Publisher\AbstractPublisher
{
    /**
     * @var integer Octal representation for directory permissions
     */
    private $_directoryPermission = 0700;

    /**
     * @var integer Octal representation for file permissions
     */
    private $_filePermission = 0600;
    
    
    /**
     * @var string Physical public root
     */
    private $_publicRoot;
    
    /**
     * Sets public root
     *
     * @param string $publicRoot
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function setPublicRoot($publicRoot)
    {
        $this->_publicRoot = $publicRoot;
        return $this;
    }


    /**
     * Returns public root
     *
     * @return string
     */
    public function getPublicRoot()
    {
        return $this->_publicRoot;
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
    
    
    
    
    
}

