<?php

namespace Emerald\Filelib\Backend;

use \MongoDb;
use \MongoId;

/**
 * MongoDB backend for Filelib
 * 
 * @author pekkis
 * @package Emerald_Filelib
 * @todo Prototype, to be error-proofed
 *
 */
class MongoBackend extends AbstractBackend implements Backend
{
    
    private $_mongo;
    
    public function setMongo(\MongoDB $mongo)
    {
        $this->_mongo = $mongo;
    }
    
    
    public function getMongo()
    {
        return $this->_mongo;
    }
    
    
    /**
     * Finds folder
     *
     * @param integer $id
     * @return \Emerald\Filelib\FolderItem|false
     */
    public function findFolder($id)
    {
        $mongo = $this->getMongo();
                
        $doc = $mongo->folders->findOne(array('_id' => new MongoId($id)));
        
        if(!$doc) {
            return false;
        }
        
        $this->_addId($doc);    
                
        return $doc;
    }

    /**
     * Finds subfolders of a folder
     *
     * @param \Emerald\Filelib\FolderItem $id
     * @return \Emerald\Filelib\FolderItemIterator
     */
    public function findSubFolders(\Emerald\Filelib\FolderItem $folder)
    {
        $mongo = $this->getMongo();

        $res = $mongo->folders->find(array('parent_id' => $folder->getId()));

        $ret = array();
        
        foreach($res as $row) {
            $this->_addId($row);
            $ret[] = $row;
        }
        
        return $ret;
                
    }
    

    /**
     * Finds all files
     *
     * @return \Emerald\Filelib\FileItemIterator
     */
    public function findAllFiles()
    {
        $mongo = $this->getMongo();

        $res = $mongo->files->find();
        
        $files = array();
        
        foreach($res as $row) {

            $file = $row;
            $this->_addId($file);
            $files[] = $file;
        }
                
        return $files;        
    }
    

    /**
     * Finds a file
     *
     * @param integer $id
     * @return \Emerald\Filelib\FileItem|false
     */
    public function findFile($id)
    {
        $mongo = $this->getMongo();
                
        $file = $mongo->files->findOne(array('_id' => new MongoId($id)));

        if(!$file) {
            return false;
        }
                
        $this->_addId($file);    
        return $file;
    }
    
    /**
     * Finds a file
     *
     * @param \Emerald\Filelib\FolderItem $folder
     * @return \Emerald\Filelib\FileItemIterator
     */
    public function findFilesIn(\Emerald\Filelib\FolderItem $folder)
    {
        $mongo = $this->getMongo();

        $res = $mongo->files->find(array('folder_id' => $folder->getId()));
        
        $files = array();

        foreach($res as $row) {

            $file = $row;
            $this->_addId($file);
            $files[] = $file;
        }
                
        return $files;        
    }
    
    /**
     * Uploads a file
     *
     * @param \Emerald\Filelib\FileUpload $upload Fileobject to upload
     * @param \Emerald\Filelib\FolderItem $folder Folder
     * @return \Emerald\Filelib\FileItem File item
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function upload(\Emerald\Filelib\FileUpload $upload, \Emerald\Filelib\FolderItem $folder, \Emerald\Filelib\FileProfile $profile)
    {

        $fileItemClass = $this->getFilelib()->getFileItemClass();

        try {

            $file = array();

            $file['folder_id'] = $folder->getId();
            $file['mimetype'] = $upload->getMimeType();
            $file['size'] = $upload->getSize();
            $file['name'] = $upload->getOverrideFilename();
            $file['profile'] = $profile->getIdentifier();
                
            $this->getMongo()->files->insert($file);
            
            $this->getMongo()->files->ensureIndex(array('folder_id' => 1, 'name' => 1), array('unique' => true));
                       
            $this->_addId($file);
            
            return $file;
            

        } catch(Exception $e) {
            throw new \Emerald\Filelib\FilelibException($e->getMessage());
        }
    	
    	
    }
    
    /**
     * Creates a folder
     *
     * @param \Emerald\Filelib\FolderItem $folder
     * @return \Emerald\Filelib\FolderItem Created folder
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function createFolder(\Emerald\Filelib\FolderItem $folder)
    {
    	$arr = $folder->toArray();
    	$this->getMongo()->folders->insert($arr);
    	$this->getMongo()->folders->ensureIndex(array('name' => 1), array('unique' => true));
    	
    	$folder->setId($arr['_id']);
    	    	
    	return $folder;
    	
    }
    

    /**
     * Deletes a folder
     *
     * @param \Emerald\Filelib\FolderItem $folder
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function deleteFolder(\Emerald\Filelib\FolderItem $folder)
    {
        $this->getMongo()->folders->remove(array('_id' => new MongoId($folder->getId())));
    
    }
    
    /**
     * Deletes a file
     *
     * @param \Emerald\Filelib\FileItem $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function deleteFile(\Emerald\Filelib\FileItem $file)
    {
        $this->getMongo()->files->remove(array('_id' => new MongoId($file->getId())));
    }
    
    /**
     * Updates a folder
     *
     * @param \Emerald\Filelib\FolderItem $folder
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function updateFolder(\Emerald\Filelib\FolderItem $folder)
    {
    	$arr = $folder->toArray();
        $this->_stripId($arr);
    	
        $this->getMongo()->folders->update(array('_id' => new MongoId($folder->getId())), $arr);
                        
        $folder->setId($arr['_id']);
        
        return $folder;
        
        
    }
    
    /**
     * Updates a file
     *
     * @param \Emerald\Filelib\FileItem $file
     * @throws \Emerald\Filelib\FilelibException When fails
     */
    public function updateFile(\Emerald\Filelib\FileItem $file)
    {
        $arr = $file->toArray();
        $this->getMongo()->files->update(array('_id' => new MongoId($file->getId())), $arr);
        return $file;
        
    }
    

        
    /**
     * Finds the root folder
     *
     * @return \Emerald\Filelib\FolderItem
     */
    public function findRootFolder()
    {
        $mongo = $this->getMongo();
        
        $root = $mongo->folders->findOne(array('parent_id' => null));
        
        if(!$root) {

            $root = array(
                'parent_id' => null,
                'name' => 'root',
                'visible' => 1,
            );
            
            $mongo->folders->save($root);
                        
        }
        
                            
       $this->_addId($root);
       return $root;
    }
    
    
    
    
    private function _addId(&$data)
    {
        $data['id'] = $data['_id']->__toString();
    }
    
    
    private function _stripId(&$data)
    {
        unset($data['id']);
    }
    
    
    
    
    
}