<?php

namespace Emerald\Filelib\File;
/**
 * Uploadable file
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class FileUpload extends \Emerald\Base\Spl\FileObject
{
    /**
     * @var string Override filename
     */
    private $_overrideFilename;

    /**
     * @var \Emerald_Filelib_Filelib
     */
    private $_filelib;

    /**
     * @var \DateTime
     */
    private $_dateUploaded;
        
    
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

    /**
     * Overrides real filename
     *
     * @param string Overriding filename
     */
    public function setOverrideFilename($filename)
    {
        $this->_overrideFilename = $filename;
    }

    /**
     * Returns filename, overridden if defined, default if not
     *
     * @return string Filename
     *
     */
    public function getOverrideFilename()
    {
        return ($this->_overrideFilename) ? $this->_overrideFilename : $this->getFilename();
    }
    
    /**
     * Returns upload date
     * 
     * @return \DateTime
     */
    public function getDateUploaded()
    {
        if(!$this->_dateUploaded) {
            $this->_dateUploaded = new \DateTime();
        }
        return $this->_dateUploaded;
    }
    
    /**
     * Sets upload date
     * 
     * @param \DateTime $dateUploaded
     */
    public function setDateUploaded(\DateTime $dateUploaded)
    {
        $this->_dateUploaded = $dateUploaded;
    }


}