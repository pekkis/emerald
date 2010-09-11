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
    public function getLinkVersion(Emerald_Filelib_FileItem $file, Emerald_Filelib_Plugin_VersionProvider_Interface $version, $prefix = true);

    /**
     * Returns a link
     *
     * @param Emerald_Filelib_FileItem $file
     * @param boolean $prefix Prefix with public directory prefix
     * @return string Link
     */
    public function getLink(Emerald_Filelib_FileItem $file, $prefix = true);

    /**
     * Creates symlink(s) for a file
     *
     * @param Emerald_Filelib_FileItem $file
     */
    // public function createSymlink(Emerald_Filelib_FileItem $file);

    /**
     * Deletes symlink(s) for a file
     *
     * @param Emerald_Filelib_FileItem $file File item
     */
    public function deleteSymlink(Emerald_Filelib_FileItem $file);

    /**
     * Returns relative link from the public to private root
     *
     * @param Emerald_Filelib_File $file File item
     * @param $levelsDown How many levels down from public root
     * @return string
     */
    // public function getRelativePathTo(Emerald_Filelib_FileItem $file, $levelsDown = 0);


}