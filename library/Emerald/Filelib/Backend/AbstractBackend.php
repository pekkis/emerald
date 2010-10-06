<?php

namespace Emerald\Filelib\Backend;

use Emerald\Filelib\FileLibrary, Emerald\Base\Options;

/**
 * Abstract backend implementing common methods
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
abstract class AbstractBackend implements BackendInterface
{
    /**
     * @var Emerald\Filelib\FileLibrary Filelib
     */
    private $_filelib;
    
    public function __construct($options)
    {
        Options::setConstructorOptions($this, $options);
    }
        
    /**
     * Sets filelib
     *
     * @param Emerald_Filelib $filelib
     */
    public function setFilelib(FileLibrary $filelib)
    {
        $this->_filelib = $filelib;
    }

    /**
     * Returns filelib
     *
     * @return Emerald\Filelib\FileLibrary
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }
    
    public function init()
    { }
    
}