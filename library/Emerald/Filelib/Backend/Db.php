<?php
/**
 * Database backend for filelib.
 * 
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_Backend_Db implements Emerald_Filelib_Backend_Interface
{

	/**
	 * @var Emerald_Filelib Filelib
	 */
	private $_filelib;
		
	/**
	 * @var Zend_Db_Adapter_Abstract Zend Db adapter
	 */
	private $_db;

	/**
	 * @var Emerald_Filelib_Backend_Db_Table_File File table
	 */
	private $_fileTable;
	
	/**
	 * @var Emerald_Filelib_Backend_Db_Table_Folder Folder table
	 */
	private $_folderTable;
		
	
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
	 * Sets db adapter
	 * 
	 * @param Zend_Db_Adapter_Abstract $db
	 * @return unknown_type
	 */
	public function setDb(Zend_Db_Adapter_Abstract $db)
	{
		$this->_db = $db;
	}
	
	
	/**
	 * Returns db adapter
	 * 
	 * @return Zend_Db_Adapter_Abstract
	 */
	public function getDb()
	{
		if(!$this->_db) {
			throw new Emerald_Filelib_Exception('Db handler has no db');
		}
		
		return $this->_db;
	}
	
	
	/**
	 * Returns file table
	 * 
	 * @return Emerald_Filelib_Backend_Db_Table_Folder
	 */
	public function getFileTable()
	{
		if(!$this->_fileTable) {
			$this->_fileTable = new Emerald_Filelib_Backend_Db_Table_File($this->getDb());
		}
		return $this->_fileTable;
	}
	
	/**
	 * Returns folder table
	 * 
	 * @return Emerald_Filelib_Backend_Db_Table_Folder
	 */
	public function getFolderTable()
	{
		if(!$this->_folderTable) {
			$this->_folderTable = new Emerald_Filelib_Backend_Db_Table_Folder($this->getDb());
		}
		
		return $this->_folderTable;
	}

	
		
	
	
	public function createFolder(Emerald_Filelib_FolderItem $folder)
	{
		throw new Emerald_Filelib_Exception('Mock fail');
	}
	
	
	public function deleteFolder(Emerald_Filelib_FolderItem $folder)
	{
		throw new Emerald_Filelib_Exception('Mock fail');
	}
	
	public function updateFolder(Emerald_Filelib_FolderItem $folder)
	{
		throw new Emerald_Filelib_Exception('Mock fail');
	}
	
	
	public function updateFile(Emerald_Filelib_FileItem $folder)
	{
		throw new Emerald_Filelib_Exception('Mock fail');
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
			throw new Emerald_Filelib_Exception($e->getMessage());
		}
		
	}
	
	public function upload(Emerald_Filelib_FileUpload $upload, Emerald_Filelib_FolderItem $folder)
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
			$this->getDb()->rollBack();
			throw new Emerald_Filelib_Exception($e->getMessage());
		}
			
			
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
	
	
	public function findAllFiles()
	{
		$res = $this->getFileTable()->fetchAll(array(), "id ASC");
		$files = array();
		foreach($res as $row) {
			$files[] = new Emerald_Filelib_FileItem($row->toArray());			
		}
		return new Emerald_Filelib_FileItemIterator($files);				
	}
	
	
	
	
	
}
?>