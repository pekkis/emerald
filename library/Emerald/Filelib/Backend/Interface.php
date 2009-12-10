<?php
/**
 * Filelib backend interface
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
interface Emerald_Filelib_Backend_Interface
{

	/**
	 * Sets filelib
	 * 
	 * @param Emerald_Filelib $filelib
	 */
	public function setFilelib(Emerald_Filelib $filelib);
	
	/**
	 * Returns filelib
	 * 
	 * @return Emerald_Filelib
	 */
	public function getFilelib();

	/**
	 * Finds folder
	 * 
	 * @param integer $id
	 * @return Emerald_Filelib_FolderItem|false
	 */
	public function findFolder($id);
	
	/**
	 * Finds subfolders of a folder
	 * 
	 * @param Emerald_Filelib_FolderItem $id
	 * @return Emerald_Filelib_FolderItemIterator
	 */
	public function findSubFolders(Emerald_Filelib_FolderItem $folder);
	
	/**
	 * Finds all files
	 * 
	 * @return Emerald_Filelib_FileItemIterator
	 */
	public function findAllFiles();
		
	/**
	 * Finds a file
	 * 
	 * @param integer $id
	 * @return Emerald_Filelib_FileItem|false
	 */
	public function findFile($id);

	/**
	 * Finds a file
	 * 
	 * @param Emerald_Filelib_FolderItem $folder
	 * @return Emerald_Filelib_FileItemIterator
	 */
	public function findFilesIn(Emerald_Filelib_FolderItem $folder);
	
	/**
	 * Uploads a file
	 * 
	 * @param Emerald_Filelib_FileUpload $upload Fileobject to upload
	 * @param Emerald_Filelib_FolderItem $folder Folder
	 * @return Emerald_Filelib_FileItem File item
	 * @throws Emerald_Filelib_Exception When fails
	 */
	public function upload(Emerald_Filelib_FileUpload $upload, Emerald_Filelib_FolderItem $folder);
		
	/**
	 * Creates a folder
	 * 
	 * @param Emerald_Filelib_FolderItem $folder
	 * @return Emerald_Filelib_FolderItem Created folder
	 * @throws Emerald_Filelib_Exception When fails
	 */
	public function createFolder(Emerald_Filelib_FolderItem $folder);
	
	
	/**
	 * Deletes a folder
	 * 
	 * @param Emerald_Filelib_FolderItem $folder
	 * @throws Emerald_Filelib_Exception When fails
	 */
	public function deleteFolder(Emerald_Filelib_FolderItem $folder);
	
	/**
	 * Deletes a file
	 * 
	 * @param Emerald_Filelib_FileItem $file
	 * @throws Emerald_Filelib_Exception When fails
	 */
	public function deleteFile(Emerald_Filelib_FileItem $file);
	
	/**
	 * Updates a folder
	 * 
	 * @param Emerald_Filelib_FolderItem $folder
	 * @throws Emerald_Filelib_Exception When fails
	 */
	public function updateFolder(Emerald_Filelib_FolderItem $folder);
	
	/**
	 * Updates a file
	 * 
	 * @param Emerald_Filelib_FileItem $file
	 * @throws Emerald_Filelib_Exception When fails
	 */
	public function updateFile(Emerald_Filelib_FileItem $file);
	
}
