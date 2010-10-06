<?php
/**
 * Abstract backend implementing common methods
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
abstract class Emerald_Filelib_Backend_AbstractBackend implements Emerald_Filelib_Backend_BackendInterface
{
    /**
     * @var Emerald\Filelib\FileLibrary Filelib
     */
    private $_filelib;
    
    public function __construct($options)
    {
        Emerald\Base\Options::setConstructorOptions($this, $options);
    }
        
    /**
     * Sets filelib
     *
     * @param Emerald_Filelib $filelib
     */
    public function setFilelib(Emerald\Filelib\FileLibrary $filelib)
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