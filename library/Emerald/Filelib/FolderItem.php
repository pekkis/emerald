<?php
/**
 * Folder item
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_FolderItem extends Emerald_Model_AbstractItem
{
	
	/**
	 * @var Emerald_Filelib Filelib
	 */
	private $_filelib;
	
	/**
	 * Sets filelib
	 * 
	 * @param Emerald_Filelib $filelib
	 */
	public function setFilelib(Emerald_Filelib $filelib)
	{
		$this->_filelib = $filelib;
	}
	
	/**
	 * Returns filelib
	 * 
	 * @return Emerald_Filelib_Filelib
	 */
	public function getFilelib()
	{
		return $this->_filelib;
	}
	
	
	/**
	 * Returns files in folder
	 * 
	 * @return Emerald_Filelib_FileItemIterator
	 */
	public function findFiles()
	{
		return $this->getFilelib()->findFilesIn($this);
	}
		
	/**
	 * Returns parent folder
	 * 
	 * @return Emerald_Filelib_FolderItem|false
	 */
	public function findParent()
	{
		if($this->parent_id) {
			return $this->getFilelib()->findFolder($this->parent_id);	
		}
		return false;
	}
	
	
}
