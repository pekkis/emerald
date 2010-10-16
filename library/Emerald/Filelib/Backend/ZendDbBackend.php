<?php

namespace Emerald\Filelib\Backend;

use Emerald\Filelib, \DateTime;

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





    public function createFolder(\Emerald\Filelib\Folder $folder)
    {
        try {
            $folderRow = $this->getFolderTable()->createRow($folder->toArray());
            
            $folderRow->save();
            	
            $folder->setId($folderRow->id);
            return $folder;
            	
        } catch(Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }


    }


    public function deleteFolder(\Emerald\Filelib\Folder $folder)
    {
        try {
            $this->getFolderTable()->delete($this->getFolderTable()->getAdapter()->quoteInto("id = ?", $folder->getId()));
        } catch(Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }

    }

    public function updateFolder(\Emerald\Filelib\Folder $folder)
    {
        try {
            $this->getFolderTable()->update(
            $folder->toArray(),
            $this->getFolderTable()->getAdapter()->quoteInto('id = ?', $folder->getId())
            );
            	
        } catch(Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }

    }


    public function updateFile(\Emerald\Filelib\File $file)
    {
        try {

            $file->setLink($file->getProfileObject()->getLinker()->getLink($file, true));
            
            $data = $file->toArray();
            $data['date_uploaded'] = $data['date_uploaded']->format('Y-m-d H:i:s');

            $this->getFileTable()->update(
            $data,
            $this->getFileTable()->getAdapter()->quoteInto('id = ?', $file->getId())
            );

            // $file->link = $file->link;
            	
        } catch(Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }

    }


    public function deleteFile(\Emerald\Filelib\File $file)
    {
        try {
            $this->getDb()->beginTransaction();
            $fileRow = $this->getFileTable()->find($file->getId())->current();
            $fileRow->delete();
            $this->getDb()->commit();
            return true;
        } catch(Exception $e) {
            $this->getDb()->rollBack();
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }

    }

    public function upload(\Emerald\Filelib\FileUpload $upload, \Emerald\Filelib\Folder $folder, \Emerald\Filelib\FileProfile $profile)
    {
        try {
                        
            
            $this->getDb()->beginTransaction();

            $file = $this->getFileTable()->createRow();
            
            $file->folder_id = $folder->getId();
            $file->mimetype = $upload->getMimeType();
            $file->size = $upload->getSize();
            $file->name = $upload->getOverrideFilename();
            $file->profile = $profile->getIdentifier();
            $file->date_uploaded = $upload->getDateUploaded()->format('Y-m-d H:i:s');
            	
            $file->save();
            	
            $this->getDb()->commit();
            	
            $ret = $file->toArray();
            $ret['date_uploaded'] = new \DateTime($ret['date_uploaded']);
            
            return $ret;

        } catch(Exception $e) {
            	
            $this->getDb()->rollBack();
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
        	
        	
    }


    public function findFolder($id)
    {
        $row = $this->getFolderTable()->find($id)->current();

        if(!$row) {
            return false;
        }

        return $row->toArray();
                
    }


    public function findRootFolder()
    {
        $row = $this->getFolderTable()->fetchRow(array('parent_id IS NULL'));
        
        if(!$row) {
            
            
            $row = $this->getFolderTable()->createRow();
            $row->name = 'root';
            $row->parent_id = null;
            $row->save();
            
        }
        
        return $row->toArray();
    }



    public function findSubFolders(\Emerald\Filelib\Folder $folder)
    {
        $folderRows = $this->getFolderTable()->fetchAll(array('parent_id = ?' => $folder->getId()));
        return $folderRows->toArray();
    }



    public function findFile($id)
    {
        $fileRow = $this->getFileTable()->find($id)->current();
        if(!$fileRow) {
            return false;
        }

        $ret = $fileRow->toArray();
        $ret['date_uploaded'] = new \DateTime($ret['date_uploaded']);
        return $ret;
        
    }


    public function findFilesIn(\Emerald\Filelib\Folder $folder)
    {
        $res = $this->getFileTable()->fetchAll(array('folder_id = ?' => $folder->getId()));
        $ret = $res->toArray();
        array_walk($ret, function(&$ret) {
            $ret['date_uploaded'] = new DateTime($ret['date_uploaded']); 
        });      
        return $ret;
    }


    public function findAllFiles()
    {
        $res = $this->getFileTable()->fetchAll(array(), "id ASC");
        
        $ret = $res->toArray();
        array_walk($ret, function(&$ret) {
            $ret['date_uploaded'] = new DateTime($ret['date_uploaded']); 
        });      
        return $ret;
        
    }





}
?>