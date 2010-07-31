<?php
/**
 * Abstract plugin class provides convenient empty methods for all hooks.
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
abstract class Emerald_Filelib_Plugin_Abstract implements Emerald_Filelib_Plugin_Interface
{

    /**
     * @var Emerald_Filelib Filelib
     */
    protected $_filelib;

    protected $_profiles;

    public function __construct($options = array())
    {
        Emerald_Options::setConstructorOptions($this, $options);
    }


    public function getProfiles()
    {
        return $this->_profiles;
    }


    public function setProfiles(array $profiles)
    {
        $this->_profiles = $profiles;
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
     * @return Emerald_Filelib_Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }

    public function init()
    { }

    public function beforeUpload(Emerald_Filelib_FileUpload $upload)
    { return $upload; }

    public function afterUpload(Emerald_Filelib_FileItem $file)
    { }

    public function beforeDelete(Emerald_Filelib_FileItem $file)
    { }

    public function afterDelete(Emerald_Filelib_FileItem $file)
    { }

    public function deleteSymlink(Emerald_Filelib_FileItem $file)
    { }

    public function createSymlink(Emerald_Filelib_FileItem $file)
    { }

}
