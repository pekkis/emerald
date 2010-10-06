<?php
/**
 * Abstract plugin class provides convenience methods for all event hooks.
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
abstract class Emerald_Filelib_Plugin_AbstractPlugin implements Emerald_Filelib_Plugin_PluginInterface
{

    /**
     * @var Emerald\Filelib\FileLibrary Filelib
     */
    protected $_filelib;

    /**
     * @var array Array of profiles
     */
    protected $_profiles;

    public function __construct($options = array())
    {
        Emerald\Base\Options::setConstructorOptions($this, $options);
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

    public function beforeUpload(Emerald\Filelib\FileUpload $upload)
    {
        return $upload;
    }

    public function afterUpload(Emerald\Filelib\FileItem $file)
    { }

    public function onDelete(Emerald\Filelib\FileItem $file)
    { }
    
    public function onPublish(Emerald\Filelib\FileItem $file)
    { }
    
    public function onUnpublish(Emerald\Filelib\FileItem $file)
    { }
    

}
