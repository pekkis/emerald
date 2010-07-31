<?php
/**
 * Interface for version providing plugins
 *
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
interface Emerald_Filelib_Plugin_VersionProvider_Interface
{

    public function setProfiles(array $profiles);

    public function getProfiles();


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

    /**
     * Concrete version creator code
     *
     * @param $file
     * @return unknown_type
     */
    public function createVersion(Emerald_Filelib_FileItem $file);


    /**
     * Concrete version deletor code
     *
     * @param $file
     * @return unknown_type
     */
    public function deleteVersion(Emerald_Filelib_FileItem $file);


}
