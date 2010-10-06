<?php

namespace Emerald\Filelib\Storage;

/**
 * Abstract storage convenience base class with common methods implemented
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
abstract class AbstractStorage implements \Emerald\Filelib\Storage\Storage
{
    /**
     * @var \Emerald\Filelib\FileLibrary Filelib
     */
    private $_filelib;
    
    public function __construct($options = array())
    {
        \Emerald\Base\Options::setConstructorOptions($this, $options);
    }
    
    /**
     * Sets filelib
     *
     * @param \Emerald_Filelib $filelib
     */
    public function setFilelib(\Emerald\Filelib\FileLibrary $filelib)
    {
        $this->_filelib = $filelib;
    }

    /**
     * Returns filelib
     *
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }
    
    
    
    
}