<?php
/**
 * Abstract convenience class for version provider plugins
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
abstract class Emerald_Filelib_Plugin_VersionProvider_Abstract
extends Emerald_Filelib_Plugin_Abstract
implements Emerald_Filelib_Plugin_VersionProvider_Interface
{
    /**
     * @var string Version identifier
     */
    protected $_identifier;


    /**
     * @var array Array of file types for which the plugin provides a version
     */
    protected $_providesFor = array();

    protected $_profiles = array();


    /**
     * @var File extension for the version
     */
    protected $_extension;


    public function setProfiles(array $profiles)
    {
        $this->_profiles = $profiles;
    }

    public function getProfiles()
    {
        return $this->_profiles;
    }



    public function afterUpload(Emerald_Filelib_FileItem $file)
    {
        if($this->providesFor($file)) {
            $this->createVersion($file);
        }
    }


    public function onPublish(Emerald_Filelib_FileItem $file)
    {
        if(!$this->providesFor($file)) {
            return;
        }

        $this->getFilelib()->getPublisher()->publishVersion($file, $this);

    }

    
    public function onUnpublish(Emerald_Filelib_FileItem $file)
    {
        if(!$this->providesFor($file)) {
            return;
        }

        $this->getFilelib()->getPublisher()->unpublishVersion($file, $this);
        
    }
    
    
    public function onDelete(Emerald_Filelib_FileItem $file)
    {
        if(!$this->providesFor($file)) {
            return;
        }
        $this->deleteVersion($file);

    }
    

    public function deleteVersion(Emerald_Filelib_FileItem $file)
    {
        $this->getFilelib()->getStorage()->deleteVersion($file, $this);
    }




    public function getRenderPath(Emerald_Filelib_FileItem $file)
    {
        if($file->isAnonymous()) {
            $link = $this->getFilelib()->getPublicDirectoryPrefix() . '/' . $file->getProfileObject()->getLinker()->getLinkVersion($file, $this);
        } else {
            $link = $this->getFilelib()->getStorage()->retrieveVersion($file, $this);
        }
        return $link;
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
     * Returns identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }



    /**
     * Sets file types for this version plugin.
     *
     * @param array $providesFor Array of file types
     */
    public function setProvidesFor(array $providesFor)
    {
        $this->_providesFor = $providesFor;
    }


    /**
     * Returns file types which the version plugin provides version for.
     *
     * @return array
     */
    public function getProvidesFor()
    {
        return $this->_providesFor;
    }


    /**
     * Returns whether the plugin provides a version for a file.
     *
     * @param Emerald_Filelib_FileItem $file File item
     * @return boolean
     */
    public function providesFor(Emerald_Filelib_FileItem $file)
    {
        if(in_array($file->getType(), $this->getProvidesFor())) {
            if(in_array($file->profile, $this->getProfiles())) {
                return true;
            }
        }

        return false;
    }



    /**
     * Sets versions file extension
     *
     * @param string $extension Extension without the prefixing dot
     */
    public function setExtension($extension)
    {
        $extension = str_replace('.', '', $extension);
        $this->_extension = $extension;
    }


    /**
     * Returns the plugins file extension without the prefixing dot.
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->_extension;
    }


    /*
     * Init registers version with identifier
     */
    public function init()
    {
        if(!$this->getIdentifier()) {
            throw new Emerald_Filelib_Exception('Version plugin must have an identifier');
        }

        if(!$this->getExtension()) {
            throw new Emerald_Filelib_Exception('Version plugin must have a file extension');
        }

        foreach($this->getProvidesFor() as $fileType) {
            foreach($this->getProfiles() as $profile) {
                $this->getFilelib()->getProfile($profile)->addFileVersion($fileType, $this->getIdentifier(), $this);
            }
        }

    }


}
