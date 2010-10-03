<?php
/**
 * An abstract linker class with common methods implemented.
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
abstract class Emerald_Filelib_Linker_Abstract implements Emerald_Filelib_Linker_Interface
{

    /**
     * @var Emerald_Filelib_FileLibrary Filelib
     */
    protected $_filelib;

    /**
     * @param array|Zend_Config $options
     */
    public function __construct($options = array())
    {
        Emerald_Base_Options::setConstructorOptions($this, $options);
    }


    /**
     * Sets filelib
     *
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

    /**
     * Initialization is run once when linker is set to filelib
     */
    public function init()
    { }
    


}
