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
     * @return Emerald\Filelib\Folder\Folder|false
     */
    public function findFolder($id);

    /**
     * Finds subfolders of a folder
     *
     * @param \Emerald\Filelib\Folder\Folder $id
     * @return \Emerald\Filelib\Folder\FolderIterator
     */
    public function findSubFolders(\Emerald\Filelib\Folder\Folder $folder);

    /**
     * Finds all files
     *
     * @return \Emerald\Filelib\File\FileIterator
     */
    public function findAllFiles();

    /**
     * Finds a file
     *
     * @param integer $id
     * @return \Emerald\Filelib\File\File|false
     */
    public function findFile($id);

    /**
     * Finds a file
     *
     * @param \Emerald\Filelib\Folder\Folder $folder
     * @return \Emerald\Filelib\File\FileIterator
     */
    public function findFilesIn(\Emerald\Filelib\Folder\Folder $folder);

    /**
     * Uploads a file
     *
     * @param \Emerald\Filelib\File\FileUpload $upload Fileobject to upload
     * @param \Emerald\Filelib\Folder\Folder $folder Folder
     * @return \Emerald\Filelib\File\File File item
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function upload(\Emerald\Filelib\File\FileUpload $upload, \Emerald\Filelib\Folder\Folder $folder, \Emerald\Filelib\File\FileProfile $profile);

    /**
     * Creates a folder
     *
     * @param Emerald\Filelib\Folder\Folder $folder
     * @return Emerald\Filelib\Folder\Folder Created folder
     * @throws Emerald\Filelib\FilelibException When fails
     */
    public function createFolder(\Emerald\Filelib\Folder\Folder $folder);


    /**
     * Deletes a folder
     *
     * @param \Emerald\Filelib\Folder\Folder $folder
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function deleteFolder(\Emerald\Filelib\Folder\Folder $folder);

    /**
     * Deletes a file
     *
     * @param \Emerald\Filelib\File\File $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function deleteFile(\Emerald\Filelib\File\File $file);

    /**
     * Updates a folder
     *
     * @param \Emerald\Filelib\Folder\Folder $folder
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function updateFolder(\Emerald\Filelib\Folder\Folder $folder);

    /**
     * Updates a file
     *
     * @param \Emerald\Filelib\File\File $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function updateFile(\Emerald\Filelib\File\File $file);
    	
    /**
     * Returns the root folder. Creates it if it does not exist.
     *
     * @return \Emerald\Filelib\Folder\Folder
     */
    public function findRootFolder();

}
