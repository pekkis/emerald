<?php
/**
 * Abstract backend implementing common methods
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
abstract class Emerald_Filelib_Backend_BackendAbstract implements Emerald_Filelib_Backend_Interface
{
    /**
     * @var Emerald_Filelib_FileLibrary Filelib
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
    public function setFilelib(Emerald_Filelib_FileLibrary $filelib)
    {
        $this->_filelib = $filelib;
    }

    /**
     * Returns filelib
     *
     * @return Emerald_Filelib_FileLibrary
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }
    
    public function init()
    { }
    
}