<?php
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
		
	
	
	
}
