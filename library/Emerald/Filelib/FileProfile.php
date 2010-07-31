<?php
class Emerald_Filelib_FileProfile
{
    private $_filelib;

    /**
     * @var Emerald_Filelib_Symlinker Symlinker
     */
    private $_symlinker;

    /**
     * @var array Versions for file types
     */
    private $_fileVersions = array();


    private $_description;

    private $_identifier;

    private $_selectable = true;

    private $_plugins = array();


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
     * @return Emerald_Filelib_Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }




    /**
     * Returns symlinker
     *
     * @return Emerald_Filelib_Symlinker_Interface
     */
    public function getSymlinker()
    {
        if(!$this->_symlinker) {
            throw new Emerald_Filelib_Exception("File profile must have a symlinker");
        }
        return $this->_symlinker;
    }


    /**
     * Sets symlinker
     *
     * @param Emerald_Filelib_Symlinker_Interface|string $symlinker
     * @return Emerald_Filelib Filelib
     */
    public function setSymlinker($symlinker)
    {
        if(!$symlinker instanceof Emerald_Filelib_Symlinker_Interface) {
            $symlinker = new $symlinker($this);
        }

        $this->_symlinker = $symlinker;

        return $this;
    }



    public function setDescription($description)
    {
        $this->_description = $description;
    }


    public function getDescription()
    {
        return $this->_description;
    }


    public function getIdentifier()
    {
        return $this->_identifier;
    }


    public function setIdentifier($identifier)
    {
        $this->_identifier = $identifier;
    }


    public function getSelectable()
    {
        return $this->_selectable;
    }


    public function setSelectable($selectable)
    {
        $this->_selectable = $selectable;
    }


    /**
     * Adds a plugin
     *
     * @param Emerald_Filelib_Plugin_Interface Plugin $plugin
     * @return Emerald_Filelib Filelib
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
     * @param string $profile string File profile
     * @param string $fileType string File type
     * @param string $versionIdentifier Version identifier
     * @param object $versionProvider Version provider reference
     * @return Emerald_Filelib Filelib
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
     * Returns versions of the specified file
     *
     * @param Emerald_Filelib_FileItem|string $fileType File item or file type
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
     * @return object Provider
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