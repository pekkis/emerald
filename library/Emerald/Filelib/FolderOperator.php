<?php

namespace Emerald\Filelib;

/**
 * Operates on folders
 * 
 * @package Emerald_Filelib
 * @author pekkis
 * 
 */
class FolderOperator extends AbstractOperator
{
    /**
     * Cache prefix
     * 
     * @var string
     */
    protected $_cachePrefix = 'emerald_filelib_folderoperator';

    /**
     * Creates a folder
     *
     * @param \Emerald\Filelib\FolderItem $folder
     * @return unknown_type
     */
    public function create(\Emerald\Filelib\FolderItem $folder)
    {
        $folder = $this->getBackend()->createFolder($folder);
        $folder->setFilelib($this->getFilelib());
    }


    /**
     * Deletes a folder
     *
     * @param \Emerald\Filelib\FolderItem $folder Folder
     */
    public function delete(\Emerald\Filelib\FolderItem $folder)
    {
        foreach($this->findSubFolders($folder) as $childFolder) {
            $this->delete($childFolder);
        }

        foreach($this->findFiles($folder) as $file) {
            $this->getFilelib()->file()->delete($file);
        }

        $this->getBackend()->deleteFolder($folder);
        $this->clearCached($folder->getId());
    }

    /**
     * Updates a folder
     *
     * @param \Emerald\Filelib\FolderItem $folder Folder
     */
    public function update(\Emerald\Filelib\FolderItem $folder)
    {
        $this->getBackend()->updateFolder($folder);

        foreach($this->findFiles($folder) as $file) {
            $this->getFilelib()->file()->update($file);
        }

        foreach($this->findSubFolders($folder) as $subFolder) {
            $this->update($subFolder);
        }
        $this->storeCached($folder->getId(), $folder);
    }



    /**
     * Finds the root folder
     *
     * @return \Emerald\Filelib\FolderItem
     */
    public function findRoot()
    {
        $folder = $this->getBackend()->findRootFolder();

        if(!$folder) {
            throw new FilelibException('Could not locate root folder', 500);
        }

        $folder = $this->_folderItemFromArray($folder);
        
        return $folder;
    }



    /**
     * Finds a folder
     *
     * @param mixed $id Folder id
     * @return \Emerald\Filelib\FolderItem
     */
    public function find($id)
    {
        if(!$folder = $this->findCached($id)) {
            $folder = $this->getBackend()->findFolder($id);
        }
        
        if(!$folder) {
            return false;
        }
        
        $folder = $this->_folderItemFromArray($folder);
        return $folder;
    }

    /**
     * Finds subfolders
     *
     * @param \Emerald_Fildlib_FolderItem $folder Folder
     * @return \Emerald\Filelib\FolderItemIterator
     */
    public function findSubFolders(\Emerald\Filelib\FolderItem $folder)
    {
        $rawFolders = $this->getBackend()->findSubFolders($folder);
        
        $folders = array();        
        foreach($rawFolders as $rawFolder) {
            $folder = $this->_folderItemFromArray($rawFolder);
            $folders[] = $folder;
        }
        return new \Emerald\Filelib\FolderItemIterator($folders);
    }


    /**
     * @param \Emerald\Filelib\FolderItem $folder Folder
     * @return \Emerald\Filelib\FileItemIterator Collection of file items
     */
    public function findFiles(\Emerald\Filelib\FolderItem $folder)
    {
        $ritems = $this->getBackend()->findFilesIn($folder);
        
        $items = array();
        foreach($ritems as $ritem) {
            $item = $this->_fileItemFromArray($ritem);
            // $item->setProfileObject($this->getFilelib()->getProfile($item->profile));
            $items[] = $item;
        }

        return $items;
    }



    

}