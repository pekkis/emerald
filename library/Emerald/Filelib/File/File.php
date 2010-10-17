<?php

namespace Emerald\Filelib\File;

/**
 * File interface
 * 
 * @author pekkis
 *
 */
interface File
{
        
    public function setId($id);
    
    public function getId();
    
    public function setFolderId($folderId);
    
    public function getFolderId();

    public function setMimetype($mimetype);
    
    public function getMimetype();
    
    public function setProfile($profile);
    
    public function getProfile();
    
    public function setSize($size);
    
    public function getSize();
    
    public function setName($name);
    
    public function getName();
    
    public function setLink($link);
    
    public function getLink();
    
    public function getDateUploaded();
    
    public function setDateUploaded(\DateTime $uploadDate);
    
    /**
     * Sets filelib
     *
     * @param \Emerald_Filelib $filelib
     */
    public function setFilelib(\Emerald\Filelib\FileLibrary $filelib);

    /**
     * Returns filelib
     *
     * @return \Emerald\Filelib\FileLibrary
     */
    public function getFilelib();

    public function toArray();
    
    public function fromArray(array $data);

    public function getProfileObject();
    
    public function getType();
    
    
}