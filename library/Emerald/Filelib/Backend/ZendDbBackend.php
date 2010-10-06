<?php

namespace Emerald\Filelib\Backend;

use Emerald\Filelib;

/**
 * Zend Db backend for filelib.
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class ZendDbBackend extends AbstractBackend implements Backend
{

    /**
     * @var Zend_Db_Adapter_Abstract Zend Db adapter
     */
    private $_db;

    /**
     * @var \Emerald\Filelib\Backend\ZendDb\FileTable File table
     */
    private $_fileTable;

    /**
     * @var \Emerald\Filelib\Backend\ZendDb\FolderTable Folder table
     */
    private $_folderTable;


    /**
     * Sets db adapter
     *
     * @param \Zend_Db_Adapter_Abstract $db
     * @return unknown_type
     */
    public function setDb(\Zend_Db_Adapter_Abstract $db)
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
            throw new FilelibException('Db handler has no db');
        }

        return $this->_db;
    }


    /**
     * Returns file table
     *
     * @return \Emerald\Filelib\Backend\ZendDb\FolderTable
     */
    public function getFileTable()
    {
        if(!$this->_fileTable) {
            $this->_fileTable = new \Emerald\Filelib\Backend\ZendDb\FileTable($this->getDb());
        }
        return $this->_fileTable;
    }

    /**
     * Returns folder table
     *
     * @return \Emerald\Filelib\Backend\ZendDb\FolderTable
     */
    public function getFolderTable()
    {
        if(!$this->_folderTable) {
            $this->_folderTable = new \Emerald\Filelib\Backend\ZendDb\FolderTable($this->getDb());
        }

        return $this->_folderTable;
    }





    public function createFolder(\Emerald\Filelib\FolderItem $folder)
    {
        try {
            $folderRow = $this->getFolderTable()->createRow($folder->toArray());
            $folderRow->save();
            	
            $folder->id = $folderRow->id;
            return $folder;
            	
        } catch(Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }


    }


    public function deleteFolder(\Emerald\Filelib\FolderItem $folder)
    {
        try {
            $this->getFolderTable()->delete($this->getFolderTable()->getAdapter()->quoteInto("id = ?", $folder->id));
        } catch(Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }

    }

    public function updateFolder(\Emerald\Filelib\FolderItem $folder)
    {
        try {
            $this->getFolderTable()->update(
            $folder->toArray(),
            $this->getFolderTable()->getAdapter()->quoteInto('id = ?', $folder->id)
            );
            	
        } catch(Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }

    }


    public function updateFile(\Emerald\Filelib\FileItem $file)
    {
        try {

            // $fileRow = $this->getFileTable()->find($file->id)->current();
            $file->link = $file->getProfileObject()->getLinker()->getLink($file, true);

            $this->getFileTable()->update(
            $file->toArray(),
            $this->getFileTable()->getAdapter()->quoteInto('id = ?', $file->id)
            );

            $file->link = $file->link;
            	
        } catch(Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }

    }


    public function deleteFile(\Emerald\Filelib\FileItem $file)
    {
        try {
            $this->getDb()->beginTransaction();
            $fileRow = $this->getFileTable()->find($file->id)->current();
            $fileRow->delete();
            $this->getDb()->commit();
            return true;
        } catch(Exception $e) {
            $this->getDb()->rollBack();
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }

    }

    public function upload(\Emerald\Filelib\FileUpload $upload, \Emerald\Filelib\FolderItem $folder, \Emerald\Filelib\FileProfile $profile)
    {
        $fileItemClass = $this->getFilelib()->getFileItemClass();

        try {

            $this->getDb()->beginTransaction();

            $file = $this->getFileTable()->createRow();

            $file->folder_id = $folder->id;
            $file->mimetype = $upload->getMimeType();
            $file->size = $upload->getSize();
            $file->name = $upload->getOverrideFilename();
            $file->profile = $profile->getIdentifier();
            	
            $file->save();
            	
            $fileItem = new $fileItemClass($file->toArray());
            $fileItem->setFilelib($this->getFilelib());
            	
            $fileItem->link = $file->link = $profile->getLinker()->getLink($fileItem, true);
            	
            $file->save();
            	
            $this->getDb()->commit();
            	
            return $fileItem;

        } catch(Exception $e) {
            	
            $this->getDb()->rollBack();
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
        	
        	
    }


    public function findFolder($id)
    {
        $folderRow = $this->getFolderTable()->find($id)->current();
        $className = $this->getFilelib()->getFolderItemClass();
        $item = new $className($folderRow->toArray());
        return $item;
    }


    public function findRootFolder()
    {
        $folderRow = $this->getFolderTable()->fetchRow(array('parent_id IS NULL'));
        $className = $this->getFilelib()->getFolderItemClass();
        $item = new $className($folderRow->toArray());
        return $item;
    }



    public function findSubFolders(\Emerald\Filelib\FolderItem $folder)
    {
        $folderRows = $this->getFolderTable()->fetchAll(array('parent_id = ?' => $folder->id));

        $folders = array();

        $className = $this->getFilelib()->getFolderItemClass();

        foreach($folderRows as $folderRow) {
            $folders[] = new $className($folderRow->toArray());
        }

        return new \Emerald\Filelib\FolderItemIterator($folders);
    }



    public function findFile($id)
    {
        $fileItemClass = $this->getFilelib()->getFileItemClass();
        $fileRow = $this->getFileTable()->find($id)->current();
        if(!$fileRow) {
            return false;
        }

        $item = new $fileItemClass($fileRow->toArray());
        return $item;
    }


    public function findFilesIn(\Emerald\Filelib\FolderItem $folder)
    {
        $fileItemClass = $this->getFilelib()->getFileItemClass();
        $res = $this->getFileTable()->fetchAll(array('folder_id = ?' => $folder->id));
        $files = array();

        foreach($res as $row) {
            $files[] = new $fileItemClass($row->toArray());
        }
        return new \Emerald\Filelib\FileItemIterator($files);
    }


    public function findAllFiles()
    {
        $fileItemClass = $this->getFilelib()->getFileItemClass();
        $res = $this->getFileTable()->fetchAll(array(), "id ASC");
        $files = array();
        foreach($res as $row) {
            $files[] = new $fileItemClass($row->toArray());
        }
        return new \Emerald\Filelib\FileItemIterator($files);
    }





}
?>