<?php
/**
 * Linker interface
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
interface Emerald_Filelib_Linker_LinkerInterface
{

    /**
     * Constructor eats filelib as parameter.
     *
     * @param Zend_Config|array $options
     */
    public function __construct($options = array());

    
    public function setFilelib(Emerald_Filelib_FileLibrary $filelib);
    
    /**
     * Returns filelib
     *
     * @return Emerald_Filelib_FileLibrary
     */
    public function getFilelib();

    /**
     * Returns link for a version of a file
     *
     * @param Emerald_Filelib_FileItem $file
     * @param Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version Version plugin
     * @return string Versioned link
     */
    public function getLinkVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_VersionProviderInterface $version);

    /**
     * Returns a link for a file
     *
     * @param Emerald_Filelib_FileItem $file
     * @return string Link
     */
    public function getLink(Emerald_Filelib_FileItem $file);

    
    /**
     * Initialization is run when a linker is set to filelib.
     */
    public function init();
    

}