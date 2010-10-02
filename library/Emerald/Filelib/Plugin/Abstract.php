<?php
/**
 * Abstract plugin class provides convenience methods for all event hooks.
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
abstract class Emerald_Filelib_Plugin_Abstract implements Emerald_Filelib_Plugin_Interface
{

    /**
     * @var Emerald_Filelib_FileLibrary Filelib
     */
    protected $_filelib;

    /**
     * @var array Array of profiles
     */
    protected $_profiles;

    public function __construct($options = array())
    {
        Emerald_Common_Options::setConstructorOptions($this, $options);
    }

    /** 
     * Returns an array of profiles attached to the plugin
     * 
     * @return array
     */
    public function getProfiles()
    {
        return $this->_profiles;
    }

    /** 
     * Sets the profiles attached to the plugin
     * 
     * @return array
     */
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
     * @return Emerald_Filelib_FileLibrary
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }

    public function init()
    { }

    public function beforeUpload(Emerald_Filelib_FileUpload $upload)
    {
        return $upload;
    }

    public function afterUpload(Emerald_Filelib_FileItem $file)
    { }

    public function onDelete(Emerald_Filelib_FileItem $file)
    { }
    
    public function onPublish(Emerald_Filelib_FileItem $file)
    { }
    
    public function onUnpublish(Emerald_Filelib_FileItem $file)
    { }
    

}
