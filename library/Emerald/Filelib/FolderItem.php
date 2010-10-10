<?php

namespace Emerald\Filelib;

/**
 * Folder item
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class FolderItem implements Folder
{
    /**
     * @var \Emerald\Filelib\FileLibrary Filelib
     */
    private $_filelib;

    private $_id;
    
    private $_parentId;
    
    private $_name;
    
    
    /**
     * Sets filelib
     *
     * @param \Emerald_Filelib $filelib
     */
    public function setFilelib(\Emerald\Filelib\FileLibrary $filelib)
    {
        $this->_filelib = $filelib;
    }

    /**
     * Returns filelib
     *
     * @return \Emerald\Filelib\FileLibrary Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }
            
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function setParentId($parentId)
    {
        $this->_parentId = $parentId;
    }
    
    public function getParentId()
    {
        return $this->_parentId;
    }
    
    public function setName($name)
    {
        $this->_name = $name;
    }
    
    public function getName()
    {
        return $this->_name;
    }
       

    
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'parent_id' => $this->getParentId(),
            'name' => $this->getName(),
        );
    }
    
    public function fromArray(array $data)
    {
        if(isset($data['id'])) {
            $this->setId($data['id']);  
        } 
        $this->setParentId($data['parent_id']);
        $this->setName($data['name']);
    }
    
    

}
