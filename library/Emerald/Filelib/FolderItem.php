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
	private $_filelib;
	
	public function setFilelib(Emerald_Filelib $filelib)
	{
		$this->_filelib = $filelib;
	}
	
	
	public function getFilelib()
	{
		return $this->_filelib; 
	}
	
	
	public function findFiles()
	{
		return $this->getFilelib()->findFilesIn($this);
	}
		
	/**
	 * @return Emerald_Filelib_FolderItem
	 */
	public function findParent()
	{
		if($this->parent_id) {
			return $this->getFilelib()->findFolder($this->parent_id);	
		}
		return false;
	}
	
	
}
