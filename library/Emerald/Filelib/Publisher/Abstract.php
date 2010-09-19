<?php
/**
 * Abstract convenience publisher base class implementing common methods
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
abstract class Emerald_Filelib_Publisher_Abstract
{
    /**
     * @var Emerald_Filelib Filelib
     */
    private $_filelib;
    
    public function __construct($options = array())
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
     * @return Emerald_Filelib Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }
    
    
    
    
}