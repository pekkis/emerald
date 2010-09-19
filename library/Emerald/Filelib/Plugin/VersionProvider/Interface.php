<?php
/**
 * Interface for version providing plugins
 *
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
interface Emerald_Filelib_Plugin_VersionProvider_Interface extends Emerald_Filelib_Plugin_Interface
{
    /**
     * Sets file extension
     *
     * @param string $extension File extension
     */
    public function setExtension($extension);

    /**
     * Returns the plugins file extension
     *
     * @return string
     */
    public function getExtension();
    
    /**
     * Returns render path
     * 
     * @param Emerald_Filelib_FileItem $file
     * @todo This whole method is ambiguous. Rethinking required.
     */
    public function getRenderPath(Emerald_Filelib_FileItem $file);
    
    /**
     * Sets file types for this version plugin.
     *
     * @param array $providesFor Array of file types
     */
    public function setProvidesFor(array $providesFor);

    /**
     * Returns file types which the version plugin provides version for.
     *
     * @return array
     */
    public function getProvidesFor();

    /**
     * Returns whether the plugin provides a version for a file.
     *
     * @param Emerald_Filelib_FileItem $file File item
     * @return boolean
     */
    public function providesFor(Emerald_Filelib_FileItem $file);

    /**
     * Sets version identifier
     *
     * @param string $identifier Unique identifier for this version
     */
    public function setIdentifier($identifier);

    /**
     * Returns version identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Returns version identifier
     *
     * @return string
     */
    public function getRenderPath(Emerald_Filelib_FileItem $file);
    
}
