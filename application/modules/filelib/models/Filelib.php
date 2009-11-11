<?php
class Filelib_Model_Filelib
{
	
	private $_db;
	
	private $_acl;
	
	private $_root;
	
	private $_publicRoot;
	
	private $_publicDirectoryPrefix = '';
	
	private $_magic;
	
	
	

	private $_fileTable;
	private $_folderTable;
	
	
	private $_filesPerDirectory = 500;
	
	private $_directoryPermission = 0700;
	private $_filePermission = 0700;
	
	
	
	public function getFilesPerDirectory()
	{
		return $this->_filesPerDirectory;
	}
	
	
	public function getDirectoryPermission()
	{
		return $this->_directoryPermission;
	}
	
	
	public function getFilePermission()
	{
		return $this->_filePermission;
	}
	
	
	public function getDirectoryId($fileId)
	{
		return ceil($fileId / $this->getFilesPerDirectory());	
	}
	
	
	
	public function setMagic($magic)
	{
		$this->_magic = $magic;
	}
	
	
	public function getMagic()
	{
		return $this->_magic;
	}
	
	
	public function getFileTable()
	{
		if(!$this->_fileTable) {
			$this->_fileTable = new Filelib_Model_DbTable_File($this->getDb());
		}
		return $this->_fileTable;
	}
	
	public function getFolderTable()
	{
		if(!$this->_folderTable) {
			$this->_folderTable = new Filelib_Model_DbTable_Folder($this->getDb());
		}
		return $this->_folderTable;
	}
	
	

	public function __construct()
	{

		
		
		
	}

	
	
	
	
	
	public function setRoot($root)
	{
		$this->_root = $root;		
	}
	
	
	public function getRoot()
	{
		return $this->_root;
	}

	
	public function setPublicDirectoryPrefix($publicDirectoryPrefix)
	{
		$this->_publicDirectoryPrefix = $publicDirectoryPrefix;				
	}
	
	
	public function getPublicDirectoryPrefix()
	{
		return $this->_publicDirectoryPrefix;
	}
		
		
	public function setPublicRoot($publicRoot)
	{
		$this->_publicRoot = $publicRoot;				
	}
	
	
	public function getPublicRoot()
	{
		return $this->_publicRoot;
	}
		
	
	
	public function setDb(Zend_Db_Adapter_Abstract $db)
	{
		$this->_db = $db;
	}
	
	
	public function getDb()
	{
		return $this->_db;
	}


	public function setAcl(Zend_Acl $acl)
	{
		$this->_acl = $acl;
	}
	
	
	public function getAcl()
	{
		return $this->_acl;
	}
	
	
	
	public function findFile($id)
	{
		return $this->getFileTable()->find($id);
	}
	
	
	public function findFolder($id)
	{
		return $this->getFolderTable()->find($id);
	}
	
	
	public function fileIsAnonymous($file)
	{
		return true;
	}
	
	
	
	public function getUpload($path)
	{
		$upload = new Filelib_Model_Upload($path);
		$upload->setFilelib($this);
		return $upload;		
	}
	
	
	public function upload(Filelib_Model_Upload $upload,  $folder)
	{
		
		if(!$upload->canUpload()) {
			
			die('can not upload');
			
		}
		
		
		$fileTbl = $this->getFileTable();
		$folderTbl = $this->getFileTable();
		
		
		try {
			
			$this->getDb()->beginTransaction();

			$file = $fileTbl->createRow();
			
			$file->folder_id = $folder->id;
			$file->mimetype = $upload->getMimeType();
			$file->size = $upload->getSize();
			$file->name = $upload->getOverrideFilename();	
			$file->save();
			
			$root = $this->getRoot();
			
			$dir = $root . '/' . $this->getDirectoryId($file->id); 
			
			if(!is_dir($dir)) {
				@mkdir($dir, $this->getDirectoryPermission(), true);
			}
						
			if(!is_dir($dir) || !is_writable($dir)) {
				throw new Filelib_Model_Exception('Could not write into directory', 500);
			}
			
			$fileTarget = $dir . '/' . $file->id;
						
			copy($upload->getRealPath(), $fileTarget);
			
			chmod($fileTarget, $this->getFilePermission());
			
			if(!is_readable($fileTarget)) {
				throw new Filelib_Model_Exception('Could not copy file to folder');
			}
						
			$this->getDb()->commit();
			
		} catch(Exception $e) {
			$this->getDb()->rollBack();
			throw $e;
		}
		
		
		if($this->fileIsAnonymous($file)) {
			$file->createSymlink();			
		}
		
		return $file;
		
	}
	
	
	public function delete(Filelib_Model_FileRow $file)
	{

		$this->getDb()->beginTransaction();
						
		try {
			
			$file->deleteSymlink();
			$path = $this->getRoot() . '/' . $this->getDirectoryId($file->id) . '/' . $file->id; 
							
			$fileObj = new SplFileObject($path);
			if(!$fileObj->isFile() || !$fileObj->isWritable()) {
				throw new Filelib_Model_Exception('Can not delete file');
			}
			
			if(!@unlink($fileObj->getPathname())) {
				throw new Filelib_Model_Exception('Can not delete file');
			}
			
			$file->delete();
			
			$this->getDb()->commit();
			
		} catch(Filelib_Model_Exception $e) {
			$this->getDb()->rollBack();
			throw $e;
		}
		
		
		return true;
		
	}
	
	
	
	public function render(Filelib_Model_FileRow $file, Zend_Controller_Response_Http $response, $download = false)
	{
		if($download) {
			$response->setHeader('Content-disposition', "attachment; filename={$file->name}");
		} elseif($this->fileIsAnonymous($file)) {
			return $response->setRedirect($this->getPublicDirectoryPrefix() . '/' . $file->iisiurl, 302);
		}
				
		$path = $file->getPath();
		if(!is_readable($path)) {
			throw new Model_Filelib_Exception('File not readable');
		}

		$response->setHeader('Content-Type', $file->mimetype);
		
		readfile($path);
						
		return;
		
	}
	
	
	
	
}
?>