<?php

namespace Emerald\Filelib\Plugin;

/**
 * Emerald Filelib plugin interface
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
interface Plugin
{

    /**
     * Sets filelib
     *
     * @param \Emerald_Filelib $filelib Filelib
     */
    public function setFilelib(\Emerald\Filelib\FileLibrary $filelib);

    /**
     * Returns filelib
     *
     * @return \Emerald\Filelib\FileLibrary
     */
    public function getFilelib();
    
    /**
     * Returns an array of profiles
     * 
     * @return array
     */
    public function getProfiles();

    /**
     * Sets profiles
     * 
     * @param array $profiles Array of profiles
     */
    public function setProfiles(array $profiles);

    /**
     * Runs when plugin is added.
     */
    public function init();
    
    /**
     * Runs before upload
     *
     * @param \Emerald\Filelib\FileUpload $upload
     * @return \Emerald\Filelib\FileUpload
     */
    public function beforeUpload(\Emerald\Filelib\FileUpload $upload);

    /**
     * Runs after succesful upload.
     *
     * @param \Emerald\Filelib\File $file
     */
    public function afterUpload(\Emerald\Filelib\File $file);

    /**
     * Runs after successful delete.
     *
     * @param \Emerald\Filelib\File $file
     */
    public function onDelete(\Emerald\Filelib\File $file);

    /**
     * Runs on publish
     *
     * @param \Emerald\Filelib\File $file
     */
    public function onPublish(\Emerald\Filelib\File $file);

    /**
     * Runs on unpublish
     *
     * @param \Emerald\Filelib\File $file
     */
    public function onUnpublish(\Emerald\Filelib\File $file);

}