<?php

namespace Emerald\Filelib;

/**
 * File item
 *
 * @author pekkis
 *
 */
class FileItem implements File
{
    /**
     * @var \Emerald\Filelib\FileLibrary Filelib
     */
    private $_filelib;

    private $_profileObj;

    
    private $_id;
    
    private $_folderId;
    
    private $_mimetype;
    
    private $_profile;
    
    private $_size;
    
    private $_name;
    
    private $_link;
        
    
    public function setId($id)
    {
        $this->_id = $id;
    }
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function setFolderId($folderId)
    {
        $this->_folderId = $folderId;
    }
    
    public function getFolderId()
    {
        return $this->_folderId;
    }

    public function setMimetype($mimetype)
    {
        $this->_mimetype = $mimetype;
    }
    
    public function getMimetype()
    {
        return $this->_mimetype;
    }
    
    public function setProfile($profile)
    {
        $this->_profile = $profile;        
    }
    
    public function getProfile()
    {
        return $this->_profile;
    }
    
    public function setSize($size)
    {
        return $this->_size;
    }
    
    public function getSize()
    {
        return $this->_size;
    }
    
    public function setName($name)
    {
        $this->_name = $name;
    }
    
    public function getName()
    {
        return $this->_name;
    }
    
    public function setLink($link)
    {
        $this->_link = $link;
    }
    
    public function getLink()
    {
        return $this->_link;
    }
    
    

    public function getProfileObject()
    {
        return $this->getFilelib()->getProfile($this->getProfile());
    }

    
    public function getType()
    {
        return $this->getFilelib()->file()->getType($this);
    }
    
    
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
     * @return \Emerald\Filelib\FileLibrary
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }

    
    public function toArray()
    {
        return array(
            'id' => $this->getId(),
            'folder_id' => $this->getFolderId(),
            'mimetype' => $this->getMimetype(),
            'profile' => $this->getProfile(),
            'size' => $this->getSize(),
            'name' => $this->getName(),
            'link' => $this->getLink(),
        );
    }
    
    
    public function fromArray(array $data)
    {
        if(isset($data['id'])) {
            $this->setId($data['id']);    
        }
        $this->setFolderId($data['folder_id']);
        $this->setMimetype($data['mimetype']);
        $this->setProfile($data['profile']);
        $this->setSize($data['size']);
        $this->setName($data['name']);
        $this->setLink($data['link']);
    }
    
    

    /**
     * Renders file's path.
     *
     * @param $opts array Render options
     * @return string Render path
     */
    
    /*
    public function renderPath($opts = array())
    {
        return $this->getFilelib()->file()->renderPath($this, $opts);
    }
    */

    /**
     * Renders file to HTTP response
     *
     * @param \Zend_Controller_Response_Http $response Response
     * @param array $opts Options
     */
    
    /*
    public function render(\Zend_Controller_Response_Http $response, $opts = array())
    {
        return $this->getFilelib()->file()->render($this, $response, $opts);
    }
    */

    /**
     * Returns whether the file is readable by anonymous.
     *
     * @return boolean
     */
    


}
