<?php
/**
 * Abstract convenience class for version provider plugins
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
abstract class Emerald_Filelib_Plugin_VersionProvider_AbstractVersionProvider extends Emerald_Filelib_Plugin_AbstractPlugin implements Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface
{
    /**
     * @var string Version identifier
     */
    protected $_identifier;

    /**
     * @var array Array of file types for which the plugin provides a version
     */
    protected $_providesFor = array();

    /**
     * @var File extension for the version
     */
    protected $_extension;
    
    /**
     * Registers a version to all profiles
     */
    public function init()
    {
        if(!$this->getIdentifier()) {
            throw new Emerald\Filelib\FilelibException('Version plugin must have an identifier');
        }

        if(!$this->getExtension()) {
            throw new Emerald\Filelib\FilelibException('Version plugin must have a file extension');
        }

        foreach($this->getProvidesFor() as $fileType) {
            foreach($this->getProfiles() as $profile) {
                $this->getFilelib()->getProfile($profile)->addFileVersion($fileType, $this->getIdentifier(), $this);
            }
        }

    }
    
    /**
     * Returns render path
     * 
     * @param Emerald\Filelib\FileItem $file
     * @todo This whole method is ambiguous. Rethinking required.
     */
    public function getRenderPath(Emerald\Filelib\FileItem $file)
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
     * @param Emerald\Filelib\FileItem $file File item
     * @return boolean
     */
    public function providesFor(Emerald\Filelib\FileItem $file)
    {
        if(in_array($file->getType(), $this->getProvidesFor())) {
            if(in_array($file->profile, $this->getProfiles())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sets file extension
     *
     * @param string $extension File extension
     */
    public function setExtension($extension)
    {
        $extension = str_replace('.', '', $extension);
        $this->_extension = $extension;
    }

    /**
     * Returns the plugins file extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->_extension;
    }

        
    public function afterUpload(Emerald\Filelib\FileItem $file)
    {
        if($this->providesFor($file)) {
            $this->createVersion($file);
        }
    }


    public function onPublish(Emerald\Filelib\FileItem $file)
    {
        if(!$this->providesFor($file)) {
            return;
        }

        $this->getFilelib()->getPublisher()->publishVersion($file, $this);

    }

    
    public function onUnpublish(Emerald\Filelib\FileItem $file)
    {
        if(!$this->providesFor($file)) {
            return;
        }

        $this->getFilelib()->getPublisher()->unpublishVersion($file, $this);
        
    }
    
    public function onDelete(Emerald\Filelib\FileItem $file)
    {
        if(!$this->providesFor($file)) {
            return;
        }
        $this->deleteVersion($file);

    }
    
    /**
     * Deletes a version
     * 
     * @param $file Emerald\Filelib\FileItem
     * 
     */
    public function deleteVersion(Emerald\Filelib\FileItem $file)
    {
        $this->getFilelib()->getStorage()->deleteVersion($file, $this);
    }



}
