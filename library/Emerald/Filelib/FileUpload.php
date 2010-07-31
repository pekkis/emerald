<?php
/**
 * Uploadable file
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_FileUpload extends Emerald_FileObject
{
    /**
     * @var string Override filename
     */
    private $_overrideFilename;

    /**
     * @var Emerald_Filelib_Filelib
     */
    private $_filelib;

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
     * Overrides real filename with the specified filename.
     *
     * @param string
     */
    public function setOverrideFilename($filename)
    {
        $this->_overrideFilename = $filename;
    }

    /**
     * Returns override filename
     *
     */
    public function getOverrideFilename()
    {
        return ($this->_overrideFilename) ? $this->_overrideFilename : $this->getFilename();
    }

    /**
     * Returns whether the file can be uploaded
     *
     * @todo Non-mock
     */
    public function canUpload()
    {
        return true;
    }

}