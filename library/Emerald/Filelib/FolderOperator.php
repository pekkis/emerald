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
     * @param \Emerald\Filelib\FolderItem $folder Folder
     */
    public function update(\Emerald\Filelib\FolderItem $folder)
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
     * @return \Emerald\Filelib\FolderItem
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
     * @return \Emerald\Filelib\FolderItem
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
     * @param \Emerald_Fildlib_FolderItem $folder Folder
     * @return \Emerald\Filelib\FolderItemIterator
     */
    public function findSubFolders(\Emerald\Filelib\FolderItem $folder)
    {
        $folders = $this->getBackend()->findSubFolders($folder);
        foreach($folders as $folder) {
            $folder->setFilelib($this->getFilelib());
        }
        return $folders;
    }


    /**
     * @param \Emerald\Filelib\FolderItem $folder Folder
     * @return \Emerald\Filelib\FileItemIterator Collection of file items
     */
    public function findFiles(\Emerald\Filelib\FolderItem $folder)
    {
        $items = $this->getBackend()->findFilesIn($folder);
        foreach($items as $item) {
            $item->setFilelib($this->getFilelib());
            $item->setProfileObject($this->getFilelib()->getProfile($item->profile));
        }

        return $items;
    }




}