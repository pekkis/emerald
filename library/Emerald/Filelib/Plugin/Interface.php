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
	 * Runs before delete
	 * 
	 * @param Emerald_Filelib_FileItem $file
	 */
	public function beforeDelete(Emerald_Filelib_FileItem $file);
	
	
	/**
	 * Runs after successful delete.
	 * 
	 * @param Emerald_Filelib_FileItem $file
	 */
	public function afterDelete(Emerald_Filelib_FileItem $file);
	
	
	/**
	 * Runs on symlink creation
	 * 
	 * @param Emerald_Filelib_FileItem $file
	 */
	public function createSymlink(Emerald_Filelib_FileItem $file);
	
	
	/**
	 * Runs on symlink delete
	 * 
	 * @param Emerald_Filelib_FileItem $file
	 */
	public function deleteSymlink(Emerald_Filelib_FileItem $file);
	
}