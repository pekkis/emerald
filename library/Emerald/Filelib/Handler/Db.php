<?php
class Emerald_Filelib_Handler_Db implements Emerald_Filelib_Handler_Interface
{
	private $_db;
		
	private $_fileTable;
	private $_folderTable;
	

	private $_filelib;
	
	
	public function setFilelib(Emerald_Filelib $filelib)
	{				
		$this->_filelib = $filelib;
	}
	
	
	public function getFilelib($filelib)
	{
		return $this->_filelib; 
	}
	
	
	public function deleteFolder(Emerald_Filelib_FolderItem $folder)
	{
		return false;
	}
	
	
	
	public function deleteFile(Emerald_Filelib_FileItem $file)
	{
		try {
			
			$this->getDb()->beginTransaction();
			
			$fileRow = $this->getFileTable()->find($file->id)->current();
			$fileRow->delete();
		
			$this->getDb()->commit();
			
			return true;
			
		} catch(Exception $e) {
			$this->getDb()->rollBack();
			
			echo $e;
			
			return false;
			
		}
		
	}
	
	
	
	public function upload($upload, $folder)
	{
		try {

			$this->getDb()->beginTransaction();

			$file = $this->getFileTable()->createRow();
						
			$file->folder_id = $folder->id;
			$file->mimetype = $upload->getMimeType();
			$file->size = $upload->getSize();
			$file->name = $upload->getOverrideFilename();	
			$file->save();
					
			$this->getDb()->commit();
			
			$file = new Emerald_Filelib_FileItem($file->toArray());
			
			return $file;
		
		} catch(Exception $e) {
			
			echo $e;
			
			$this->getDb()->rollBack();
			
			return false;
		}
			
			
	}
	
	
	
	
	
	public function setDb(Zend_Db_Adapter_Abstract $db)
	{
		$this->_db = $db;
	}
	
	
	public function getDb()
	{
		if(!$this->_db) {
			throw new Emerald_Filelib_Exception('Db handler has no db');
		}
		
		return $this->_db;
	}

	
	public function getFileTable()
	{
		if(!$this->_fileTable) {
			$this->_fileTable = new Emerald_Filelib_Handler_Db_Table_File($this->getDb());
		}
		return $this->_fileTable;
	}
	
	public function getFolderTable()
	{
		if(!$this->_folderTable) {
			$this->_folderTable = new Emerald_Filelib_Handler_Db_Table_Folder($this->getDb());
		}
		
		return $this->_folderTable;
	}
	
	public function findFolder($id)
	{
		$folderRow = $this->getFolderTable()->find($id)->current();
		$item = new Emerald_Filelib_FolderItem($folderRow->toArray());
		return $item;		
	}
	
	
	public function findFile($id)
	{
		$fileRow = $this->getFileTable()->find($id)->current();
		$item = new Emerald_Filelib_FileItem($fileRow->toArray());
		return $item;		
	}
	
	
	public function findFilesIn(Emerald_Filelib_FolderItem $folder)
	{
		$res = $this->getFileTable()->fetchAll(array('folder_id = ?' => $folder->id));
		$files = array();
		foreach($res as $row) {
			$files[] = new Emerald_Filelib_FileItem($row->toArray());			
		}
		return new Emerald_Filelib_FileItemIterator($files);				
	}
	
	
	
	
}
?>