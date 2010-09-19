<?php
/**
 * Emerald Filelib plugin interface
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
interface Emerald_Filelib_Plugin_Interface
{

    /**
     * Sets filelib
     *
     * @param Emerald_Filelib $filelib Filelib
     */
    public function setFilelib(Emerald_Filelib $filelib);

    /**
     * Returns filelib
     *
     * @return Emerald_Filelib
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
     * @param Emerald_Filelib_FileUpload $upload
     * @return Emerald_Filelib_FileUpload
     */
    public function beforeUpload(Emerald_Filelib_FileUpload $upload);

    /**
     * Runs after succesful upload.
     *
     * @param Emerald_Filelib_FileItem $file
     */
    public function afterUpload(Emerald_Filelib_FileItem $file);

    /**
     * Runs after successful delete.
     *
     * @param Emerald_Filelib_FileItem $file
     */
    public function onDelete(Emerald_Filelib_FileItem $file);

    /**
     * Runs on publish
     *
     * @param Emerald_Filelib_FileItem $file
     */
    public function onPublish(Emerald_Filelib_FileItem $file);

    /**
     * Runs on unpublish
     *
     * @param Emerald_Filelib_FileItem $file
     */
    public function onUnpublish(Emerald_Filelib_FileItem $file);

}