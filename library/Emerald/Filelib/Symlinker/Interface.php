<?php
interface Emerald_Filelib_Symlinker_Interface
{

    /**
     * Constructor eats filelib as parameter.
     *
     * @param Zend_Config|array $options
     */
    public function __construct($options = array());

    /**
     * Returns filelib
     *
     * @return Emerald_Filelib
     */
    public function getFilelib();

    /**
     * Returns a versioned link
     *
     * @param Emerald_Filelib_FileItem $file
     * @param Emerald_Filelib_Plugin_VersionProvider_Interface $version Version plugin
     * @param boolean $prefix Prefix with public directory prefix
     * @return string Versioned link
     */
    public function getLinkVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version);

    /**
     * Returns a link
     *
     * @param Emerald_Filelib_FileItem $file
     * @param boolean $prefix Prefix with public directory prefix
     * @return string Link
     */
    public function getLink(Emerald_Filelib_FileItem $file);


}