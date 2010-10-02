<?php
/**
 * File profile
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class Emerald_Filelib_FileProfile
{
    /**
     * @var Emerald_Filelib_FileLibrary
     */
    private $_filelib;

    /**
     * @var Emerald_Filelib_Linker Linker
     */
    private $_linker;

    /**
     * @var array Versions for file types
     */
    private $_fileVersions = array();
    
    /**
     * @var string Human readable identifier
     */
    private $_description;

    /**
     * @var string Machine readable identifier
     */
    private $_identifier;

    /**
     * @var boolean Selectable (in uis for example)
     */
    private $_selectable = true;

    /**
     * @var array Array of plugins
     */
    private $_plugins = array();

    
    public function __construct($options = array())
    {
        Emerald_Common_Options::setConstructorOptions($this, $options);
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
     * @return Emerald_Filelib_Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }

    /**
     * Returns linker
     *
     * @return Emerald_Filelib_Linker_Interface
     */
    public function getLinker()
    {
        if(!$this->_linker) {
            throw new Emerald_Filelib_Exception("File profile '{$this->getIdentifier()}' does not have a linker");
        }
        return $this->_linker;
    }


    /**
     * Sets linker
     *
     * @param Emerald_Filelib_Linker_Interface|string $linker
     * @return Emerald_Filelib_FileLibrary Filelib
     */
    public function setLinker($linker)
    {
        if(!$linker instanceof Emerald_Filelib_Linker_Interface) {
            $linker = new $linker($this);

        }
        $linker->init();
        $this->_linker = $linker;

        return $this;
    }

    /**
     * Sets human readable identifier
     * 
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->_description = $description;
    }


    /**
     * Returns human readable identifier
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->_description;
    }


    /**
     * Returns identifier
     * 
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }


    /**
     * Sets identifier
     * 
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->_identifier = $identifier;
    }


    /**
     * Returns whether profile is selectable
     * 
     * @return boolean
     */
    public function getSelectable()
    {
        return $this->_selectable;
    }


    /**
     * Sets whether the profile is selectable
     * 
     * @param boolean $selectable
     */
    public function setSelectable($selectable)
    {
        $this->_selectable = $selectable;
    }


    /**
     * Adds a plugin
     *
     * @param Emerald_Filelib_Plugin_Interface Plugin $plugin
     * @return Emerald_Filelib_FileProfile
     */
    public function addPlugin(Emerald_Filelib_Plugin_Interface $plugin)
    {
        $this->_plugins[] = $plugin;
        return $this;
    }

    /**
     * Returns all plugins
     *
     * @return array Array of plugins
     */
    public function getPlugins()
    {
        return $this->_plugins;
    }


    /**
     * Adds a file version
     *
     * @param string $fileType string File type
     * @param string $versionIdentifier Version identifier
     * @param object $versionProvider Version provider reference
     * @return Emerald_Filelib_FileProfile
     */
    public function addFileVersion($fileType, $versionIdentifier, $versionProvider)
    {
        if(!isset($this->_fileVersions[$fileType])) {
            $this->_fileVersions[$fileType] = array();
        }
        $this->_fileVersions[$fileType][$versionIdentifier] = $versionProvider;

        return $this;
    }


    /**
     * Returns all defined versions of a file
     *
     * @param Emerald_Filelib_FileItem $fileType File item
     * @return array Array of provided versions
     */
    public function getFileVersions(Emerald_Filelib_FileItem $file)
    {
        $fileType = $file->getType();

        if(!isset($this->_fileVersions[$fileType])) {
            $this->_fileVersions[$fileType] = array();
        }

        return array_keys($this->_fileVersions[$fileType]);

    }



    /**
     * Returns whether a file has a certain version
     *
     * @param Emerald_Filelib_FileItem $file File item
     * @param string $version Version
     * @return boolean
     */
    public function fileHasVersion(Emerald_Filelib_FileItem $file, $version)
    {
        $filetype = $this->getFilelib()->file()->getType($file);

        if(isset($this->_fileVersions[$filetype][$version])) {
            return true;
        }
        return false;
    }

    /**
     * Returns version provider for a file/version
     *
     * @param Emerald_Filelib_FileItem $file File item
     * @param string $version Version
     * @return Emerald_Filelib_Plugin_VersionProvider_Abstract Provider
     */
    public function getVersionProvider(Emerald_Filelib_FileItem $file, $version)
    {
        $filetype = $this->getFilelib()->file()->getType($file);
        return $this->_fileVersions[$filetype][$version];
    }




    public function __toString()
    {
        return $this->getIdentifier();
    }

}