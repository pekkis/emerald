<?php
/**
 * Abstract backend implementing common methods
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
abstract class Emerald_Filelib_Backend_BackendAbstract
{
    /**
     * @var Emerald_Filelib Filelib
     */
    private $_filelib;
    
    public function __construct($options)
    {
        Emerald_Options::setConstructorOptions($this, $options);
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
     * @return Emerald_Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }
    
    public function init()
    { }
    
}