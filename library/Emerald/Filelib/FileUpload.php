<?php
/**
 * Uploadable file
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_FileUpload extends Emerald_Common_Spl_FileObject
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
     * @return Emerald_Filelib
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


}