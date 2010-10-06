<?php

namespace Emerald\Filelib\Linker;

/**
 * Linker interface
 * 
 * @author pekkis
 * @package \Emerald_Filelib
 *
 */
interface Linker
{

    /**
     * Constructor eats filelib as parameter.
     *
     * @param \Zend_Config|array $options
     */
    public function __construct($options = array());

    
    public function setFilelib(\Emerald\Filelib\FileLibrary $filelib);
    
    /**
     * Returns filelib
     *
     * @return \Emerald\Filelib\FileLibrary
     */
    public function getFilelib();

    /**
     * Returns link for a version of a file
     *
     * @param \Emerald\Filelib\FileItem $file
     * @param \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version Version plugin
     * @return string Versioned link
     */
    public function getLinkVersion(\Emerald\Filelib\FileItem $file, \Emerald\Filelib\Plugin\VersionProvider\VersionProvider $version);

    /**
     * Returns a link for a file
     *
     * @param \Emerald\Filelib\FileItem $file
     * @return string Link
     */
    public function getLink(\Emerald\Filelib\FileItem $file);

    
    /**
     * Initialization is run when a linker is set to filelib.
     */
    public function init();
    

}