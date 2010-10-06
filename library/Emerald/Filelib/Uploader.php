<?php

namespace Emerald\Filelib;

/**
 * Defines the file types that are allowed / denied to be uploaded
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Uploader
{

    /**
     * @var Emerald\Filelib\FileLibrary
     */
    private $_filelib;

    /**
     * @var array
     */
    private $_accepted = array();

    /**
     * @var array
     */
    private $_denied = array();

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

    /**
     * Accept a file type. A regex or an array of regexes to accept.
     * 
     * @param mixed $what 
     * @return Emerald\Filelib\Uploader
     */
    public function accept($what)
    {
        if(!is_array($what)) {
            $what = array($what);
        }

        foreach($what as $w) {
            $accept = "[" . $w . "]";
            $this->_accepted[] = $accept;

            if(in_array($accept, $this->_denied)) {
                unset($this->_denied[$accept]);
            }

        }

        return $this;

    }


    /**
     * Deny a file type. A regex or an array of regexes to deny.
     * 
     * @param mixed $what 
     * @return Emerald\Filelib\Uploader
     */
    public function deny($what)
    {
        if(!is_array($what)) {
            $what = array($what);
        }

        foreach($what as $w) {
            $deny = "[" . $w . "]";
            $this->_denied[] = $deny;
             
            if(in_array($deny, $this->_accepted)) {
                unset($this->_accepted[$deny]);
            }
             
        }

        return $this;

    }

    /**
     * Returns all accepted types
     * 
     * @return array
     */
    public function getAccepted()
    {
        return $this->_accepted;
    }

    /**
     * Returns all denied types
     * 
     * @return array
     */
    public function getDenied()
    {
        return $this->_denied;
    }

    /**
     * Returns whether a file upload may be uploaded
     * 
     * @param Emerald\Filelib\FileUpload $upload
     * @return boolean
     */
    public function isAccepted(Emerald\Filelib\FileUpload $upload)
    {
        $mimeType = $upload->getMimeType();

        if(!$this->getAccepted() && !$this->getDenied()) {
            return true;
        }

        foreach($this->getDenied() as $denied) {
            if(preg_match($denied, $mimeType)) {
                return false;
            }
        }

        if(!$this->getAccepted()) {
            return true;
        }

        foreach($this->getAccepted() as $accepted) {
            if(preg_match($accepted, $mimeType)) {
                return true;
            }
        }

        return false;

    }


}

