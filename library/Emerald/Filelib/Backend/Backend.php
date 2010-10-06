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
     * @return Emerald\Filelib\FolderItem|false
     */
    public function findFolder($id);

    /**
     * Finds subfolders of a folder
     *
     * @param \Emerald\Filelib\FolderItem $id
     * @return \Emerald\Filelib\FolderItemIterator
     */
    public function findSubFolders(\Emerald\Filelib\FolderItem $folder);

    /**
     * Finds all files
     *
     * @return \Emerald\Filelib\FileItemIterator
     */
    public function findAllFiles();

    /**
     * Finds a file
     *
     * @param integer $id
     * @return \Emerald\Filelib\FileItem|false
     */
    public function findFile($id);

    /**
     * Finds a file
     *
     * @param \Emerald\Filelib\FolderItem $folder
     * @return \Emerald\Filelib\FileItemIterator
     */
    public function findFilesIn(\Emerald\Filelib\FolderItem $folder);

    /**
     * Uploads a file
     *
     * @param \Emerald\Filelib\FileUpload $upload Fileobject to upload
     * @param \Emerald\Filelib\FolderItem $folder Folder
     * @return \Emerald\Filelib\FileItem File item
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function upload(\Emerald\Filelib\FileUpload $upload, \Emerald\Filelib\FolderItem $folder, \Emerald\Filelib\FileProfile $profile);

    /**
     * Creates a folder
     *
     * @param Emerald\Filelib\FolderItem $folder
     * @return Emerald\Filelib\FolderItem Created folder
     * @throws Emerald\Filelib\FilelibException When fails
     */
    public function createFolder(\Emerald\Filelib\FolderItem $folder);


    /**
     * Deletes a folder
     *
     * @param \Emerald\Filelib\FolderItem $folder
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function deleteFolder(\Emerald\Filelib\FolderItem $folder);

    /**
     * Deletes a file
     *
     * @param \Emerald\Filelib\FileItem $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function deleteFile(\Emerald\Filelib\FileItem $file);

    /**
     * Updates a folder
     *
     * @param \Emerald\Filelib\FolderItem $folder
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function updateFolder(\Emerald\Filelib\FolderItem $folder);

    /**
     * Updates a file
     *
     * @param \Emerald\Filelib\FileItem $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function updateFile(\Emerald\Filelib\FileItem $file);


    	
    /**
     * Finds the root folder
     *
     * @return \Emerald\Filelib\FolderItem
     */
    public function findRootFolder();

}
