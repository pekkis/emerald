<?php
/**
 * An abstract symlinker class with common methods implemented.
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
abstract class Emerald_Filelib_Symlinker_Abstract
{

    /**
     * @var Emerald_Filelib Filelib
     */
    protected $_filelib;

    /**
     * @param array|Zend_Config $options
     */
    public function __construct($options = array())
    {
        Emerald_Options::setConstructorOptions($this, $options);
    }


    /**
     * Sets filelib
     *
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


    /**
     * Creates symlink(s) for a file
     *
     * @param Emerald_Filelib_FileItem $file
     */
    public function createSymlink(Emerald_Filelib_FileItem $file)
    {

        
        die('destructo!');
        
    }


    /**
     * Deletes symlink(s) for a file
     *
     * @param Emerald_Filelib_FileItem $file File item
     */
    public function deleteSymlink(Emerald_Filelib_FileItem $file)
    {

        die('newver again!!!!');
        

    }



}
