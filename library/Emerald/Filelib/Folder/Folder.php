<?php

namespace Emerald\Filelib\Folder;

/**
 * Folder interface
 * 
 * @author pekkis
 *
 */
interface Folder
{

    /**
     * Sets filelib
     *
     * @param \Emerald_Filelib $filelib
     */
    public function setFilelib(\Emerald\Filelib\FileLibrary $filelib);

    /**
     * Returns filelib
     *
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function getFilelib();

    /**
     * Returns the standardized array representation of folder
     * 
     * @return array
     */
    
    public function toArray();
    
    /**
     * Populates object from the standardized array representation
     * 
     * @param array $data
     */
    public function fromArray(array $data);
            
    /**
     * Sets id
     * 
     * @param mixed $id
     */
    public function setId($id);
    
    /**
     * Returns id
     * 
     * @return mixed
     */
    public function getId();
    
    /**
     * Sets parent id
     * 
     * @param mixed $parentId
     */
    public function setParentId($parentId);
    
    /**
     * Returns parent id
     * 
     * @return mixed
     */
    public function getParentId();
    
    /**
     * Sets name
     * 
     * @param string $name
     */
    public function setName($name);
    
    /**
     * Returns name
     * 
     * @return string
     */
    public function getName();
    
}