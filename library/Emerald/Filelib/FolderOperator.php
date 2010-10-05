<?php
/**
 * Operates on folders
 * 
 * @package Emerald_Filelib
 * @author pekkis
 * 
 */
class Emerald_Filelib_FolderOperator
{
    /**
     * @var Zend_Cache_Core
     */
    protected $_cache;

    /**
     * Cache prefix
     * 
     * @var string
     */
    protected $_cachePrefix = 'emerald_filelib_folderoperator';
   
    public function __construct(Emerald_Filelib_FileLibrary $filelib)
    {
        $this->_filelib = $filelib;
        $this->_backend = $filelib->getBackend();
    }
        
    
    /**
     * Returns cache
     * 
     * @return Zend_Cache_Core
     */
    public function getCache()
    {
        if(!$this->_cache) {
            $this->_cache = $this->getFilelib()->getCache();
        }
        return $this->_cache;
    }


    /**
     * Returns cache identifier
     * 
     * @param mixed $id Id
     * @return string
     */
    public function getCacheIdentifier($id)
    {
        if(is_array($id)) {
            $id = implode('_', $id);
        }
        return $this->_cachePrefix . '_' . $id;
    }


    /**
     * Tries to load folder from cache, returns object on success.
     * 
     * @param mixed $id
     * @return mixed 
     */
    public function findCached($id) {
        return $this->getCache()->load($this->getCacheIdentifier($id));
    }


    /**
     * Clears cache for id
     * 
     * @param mixed $id
     */
    public function clearCached($id)
    {
        $this->getCache()->remove($this->getCacheIdentifier($id));
    }


    /**
     * Stores folder to cache
     * 
     * @param mixed $id
     * @param mixed $data
     */
    public function storeCached($id, $data)
    {
        $this->getCache()->save($data, $this->getCacheIdentifier($id));
    }

    /**
     * Returns backend
     *
     * @return Emerald_Filelib_Backend_BackendInterface
     */
    public function getBackend()
    {
        return $this->_backend;
    }

    /**
     * Returns filelib
     *
     * @return Emerald_Filelib_FileLibrary
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }

    /**
     * Creates a folder
     *
     * @param Emerald_Filelib_FolderItem $folder
     * @return unknown_type
     */
    public function create(Emerald_Filelib_FolderItem $folder)
    {
        $folder = $this->getBackend()->createFolder($folder);
        $folder->setFilelib($this->getFilelib());
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
        $this->clearCached($folder->id);
    }

    /**
     * Updates a folder
     *
     * @param Emerald_Filelib_FolderItem $folder Folder
     */
    public function update(Emerald_Filelib_FolderItem $folder)
    {
        $this->getBackend()->updateFolder($folder);

        $files = $folder->findFiles();

        foreach($folder->findFiles() as $file) {
            $this->getFilelib()->file()->update($file);
        }

        foreach($folder->findSubFolders() as $subFolder) {
            $this->update($subFolder);
        }
        $this->storeCached($folder->id, $folder);
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
        if(!$folder = $this->findCached($id)) {
            $folder = $this->getBackend()->findFolder($id);
        }
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