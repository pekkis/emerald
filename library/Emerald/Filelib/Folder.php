<?php

namespace Emerald\Filelib;

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

    public function toArray();
    
    public function fromArray(array $data);
            
    public function setId($id);
    
    public function getId();
    
    public function setParentId($parentId);
    
    public function getParentId();
    
    public function setName($name);
    
    public function getName();
    
}