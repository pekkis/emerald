<?php
class Emerald_Filelib_FolderOperator
{
	
	/**
	 * Returns backend
	 * 
	 * @return Emerald_Filelib_Backend_Interface
	 */
	public function getBackend()
	{
		return $this->_backend;
	}
	
	
	/**
	 * Returns filelib
	 * 
	 * @return Emerald_Filelib
	 */
	public function getFilelib()
	{
		return $this->_filelib;
	}
	
	
	public function __construct(Emerald_Filelib $filelib)
	{
		$this->_filelib = $filelib;
		$this->_backend = $filelib->getBackend();
	}

	
	
	/**
	 * Creates a folder
	 * 
	 * @param Emerald_Filelib_FolderItem $folder
	 * @return unknown_type
	 */
	public function create(Emerald_Filelib_FolderItem $folder)
	{
		return $this->getBackend()->createFolder($folder);
	}
	
	
	/**
	 * Deletes a folder
	 * 
	 * @param Emerald_Filelib_FolderItem $folder Folder
	 */
	public function delete(Emerald_Filelib_FolderItem $folder)
	{
		foreach($folder->findSubFolders() as $childFolder) {
			$this->delete($childFolder);
		}

		foreach($folder->findFiles() as $file) {
			$this->getFilelib()->file()->delete($file);	
		}
		
		$this->getBackend()->deleteFolder($folder);
	}
	
	/**
	 * Updates a folder
	 * 
	 * @param Emerald_Filelib_FolderItem $folder Folder
	 */
	public function update(Emerald_Filelib_FolderItem $folder)
	{
		$this->getBackend()->updateFolder($folder);

		foreach($folder->findFiles() as $file) {
			$this->getFilelib()->file()->update($file);
		}
		
		foreach($folder->findSubFolders() as $subFolder) {
			$this->update($subFolder);
		}
	}
	
	
	
		/**
	 * Finds the root folder
	 * 
	 * @return Emerald_Filelib_FolderItem
	 */
	public function findRoot()
	{
		$folder = $this->getBackend()->findRootFolder();
		$folder->setFilelib($this->getFilelib());
		return $folder;
	}
	
	
	
	/**
	 * Finds a folder
	 * 
	 * @param mixed $id Folder id
	 * @return Emerald_Filelib_FolderItem
	 */
	public function find($id)
	{
		$folder = $this->getBackend()->findFolder($id);
		$folder->setFilelib($this->getFilelib());
		return $folder;
	}
	
	/**
	 * Finds subfolders
	 * 
	 * @param Emerald_Fildlib_FolderItem $folder Folder
	 * @return Emerald_Filelib_FolderItemIterator
	 */
	public function findSubFolders(Emerald_Filelib_FolderItem $folder)
	{
		$folders = $this->getBackend()->findSubFolders($folder);
		foreach($folders as $folder) {
			$folder->setFilelib($this->getFilelib());
		}
		return $folders;
	}

	
	/**
	 * @param Emerald_Filelib_FolderItem $folder Folder
	 * @return Emerald_Filelib_FileItemIterator Collection of file items
	 */
	public function findFiles(Emerald_Filelib_FolderItem $folder)
	{
		$items = $this->getBackend()->findFilesIn($folder);
		foreach($items as $item) {
			$item->setFilelib($this->getFilelib());
			$item->setProfileObject($this->getFilelib()->getProfile($item->profile));
		}
		
		return $items;
	}
	
	
	
	
}