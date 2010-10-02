<?php
/**
 * Abstract storage convenience base class with common methods implemented
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
abstract class Emerald_Filelib_Storage_Abstract
{
    /**
     * @var Emerald_Filelib_FileLibrary Filelib
     */
    private $_filelib;
    
    public function __construct($options = array())
    {
        Emerald_Common_Options::setConstructorOptions($this, $options);
    }
    
    /**
     * Sets filelib
     *
     * @param Emerald_Filelib $filelib
     */
    public function setFilelib(Emerald_Filelib $filelib)
    {
        $this->_filelib = $filelib;
    }

    /**
     * Returns filelib
     *
     * @return Emerald_Filelib Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }
    
    
    
    
}