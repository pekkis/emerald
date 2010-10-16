<?php

namespace Emerald\Filelib\Backend;

/**
 * Filelib backend interface
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
interface Backend
{

    /**
     * Initialization. Is run when backend is set to filelib.
     */
    public function init();

    /**
     * Finds folder
     *
     * @param integer $id
     * @return Emerald\Filelib\Folder|false
     */
    public function findFolder($id);

    /**
     * Finds subfolders of a folder
     *
     * @param \Emerald\Filelib\Folder $id
     * @return \Emerald\Filelib\FolderIterator
     */
    public function findSubFolders(\Emerald\Filelib\Folder $folder);

    /**
     * Finds all files
     *
     * @return \Emerald\Filelib\FileIterator
     */
    public function findAllFiles();

    /**
     * Finds a file
     *
     * @param integer $id
     * @return \Emerald\Filelib\File|false
     */
    public function findFile($id);

    /**
     * Finds a file
     *
     * @param \Emerald\Filelib\Folder $folder
     * @return \Emerald\Filelib\FileIterator
     */
    public function findFilesIn(\Emerald\Filelib\Folder $folder);

    /**
     * Uploads a file
     *
     * @param \Emerald\Filelib\FileUpload $upload Fileobject to upload
     * @param \Emerald\Filelib\Folder $folder Folder
     * @return \Emerald\Filelib\File File item
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function upload(\Emerald\Filelib\FileUpload $upload, \Emerald\Filelib\Folder $folder, \Emerald\Filelib\FileProfile $profile);

    /**
     * Creates a folder
     *
     * @param Emerald\Filelib\Folder $folder
     * @return Emerald\Filelib\Folder Created folder
     * @throws Emerald\Filelib\FilelibException When fails
     */
    public function createFolder(\Emerald\Filelib\Folder $folder);


    /**
     * Deletes a folder
     *
     * @param \Emerald\Filelib\Folder $folder
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function deleteFolder(\Emerald\Filelib\Folder $folder);

    /**
     * Deletes a file
     *
     * @param \Emerald\Filelib\File $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function deleteFile(\Emerald\Filelib\File $file);

    /**
     * Updates a folder
     *
     * @param \Emerald\Filelib\Folder $folder
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function updateFolder(\Emerald\Filelib\Folder $folder);

    /**
     * Updates a file
     *
     * @param \Emerald\Filelib\File $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function updateFile(\Emerald\Filelib\File $file);


    	
    /**
     * Finds the root folder
     *
     * @return \Emerald\Filelib\Folder
     */
    public function findRootFolder();

}
