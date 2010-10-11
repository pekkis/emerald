<?php

namespace Emerald\Filelib\Plugin\VersionProvider;

/**
 * Interface for version providing plugins
 *
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
interface VersionProvider extends \Emerald\Filelib\Plugin\Plugin
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
     * @param \Emerald\Filelib\FileItem $file File item
     * @return boolean
     */
    public function providesFor(\Emerald\Filelib\FileItem $file);

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

    
}
