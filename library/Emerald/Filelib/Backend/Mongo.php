<?php
class Emerald_Filelib_Backend_Mongo extends Emerald_Filelib_Backend_BackendAbstract implements Emerald_Filelib_Backend_Interface
{
    
    private $_mongo;
    
    public function setMongo(MongoDB $mongo)
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
     * @return Emerald_Filelib_FolderItem|false
     */
    public function findFolder($id)
    {
        $mongo = $this->getMongo();
                
        $folder = $mongo->folders->findOne(array('_id' => new MongoId($id)));
        
        if($folder) {
            $className = $this->getFilelib()->getFolderItemClass();
            $folder = new $className($folder);
            $this->_addId($folder);    
        }
        
        return $folder;
    }

    /**
     * Finds subfolders of a folder
     *
     * @param Emerald_Filelib_FolderItem $id
     * @return Emerald_Filelib_FolderItemIterator
     */
    public function findSubFolders(Emerald_Filelib_FolderItem $folder)
    {
        $mongo = $this->getMongo();

        $folderItemClass = $this->getFilelib()->getFolderItemClass();
        $res = $mongo->folders->find(array('parent_id' => $folder->id));
        
        $folders = array();

        foreach($res as $row) {
            $folder = new $folderItemClass($row->toArray());
            $this->_addId($folder);
            $folders[] = $folder;
        }
        
        return new Emerald_Filelib_FolderItemIterator($folders);        
    }
    

    /**
     * Finds all files
     *
     * @return Emerald_Filelib_FileItemIterator
     */
    public function findAllFiles()
    {
        $mongo = $this->getMongo();

        $fileItemClass = $this->getFilelib()->getFileItemClass();
        $res = $mongo->files->find();
        
        $files = array();

        foreach($res as $row) {
            $file = new $fileItemClass($row);
            $this->_addId($file);
            $files[] = $file;
        }
                
        return $files;        
    }
    

    /**
     * Finds a file
     *
     * @param integer $id
     * @return Emerald_Filelib_FileItem|false
     */
    public function findFile($id)
    {
        $mongo = $this->getMongo();
                
        $file = $mongo->files->findOne(array('_id' => new MongoId($id)));
        
        if($file) {
            $className = $this->getFilelib()->getFileItemClass();
            $file = new $className($file);
            $this->_addId($file);    
        }
        
        return $file;
    }
    
    /**
     * Finds a file
     *
     * @param Emerald_Filelib_FolderItem $folder
     * @return Emerald_Filelib_FileItemIterator
     */
    public function findFilesIn(Emerald_Filelib_FolderItem $folder)
    {
        $mongo = $this->getMongo();

        $fileItemClass = $this->getFilelib()->getFileItemClass();
        $res = $mongo->files->find(array('folder_id' => $folder->id));
        
        $files = array();

        foreach($res as $row) {
            $file = new $fileItemClass($row);
            $this->_addId($file);
            $files[] = $file;
        }
                
        return $files;        
    }
    
    /**
     * Uploads a file
     *
     * @param Emerald_Filelib_FileUpload $upload Fileobject to upload
     * @param Emerald_Filelib_FolderItem $folder Folder
     * @return Emerald_Filelib_FileItem File item
     * @throws Emerald_Filelib_Exception When fails
     */
    public function upload(Emerald_Filelib_FileUpload $upload, Emerald_Filelib_FolderItem $folder, Emerald_Filelib_FileProfile $profile)
    {

        $fileItemClass = $this->getFilelib()->getFileItemClass();

        try {

            $file = array();

            $file['folder_id'] = $folder->id;
            $file['mimetype'] = $upload->getMimeType();
            $file['size'] = $upload->getSize();
            $file['name'] = $upload->getOverrideFilename();
            $file['profile'] = $profile->getIdentifier();
                
            $this->getMongo()->files->insert($file);
            
            $this->getMongo()->files->ensureIndex(array('folder_id' => 1, 'name' => 1), array('unique' => true));
                            
            $fileItem = new $fileItemClass($file);
            
            // @todo: Why here?
            $fileItem->setFilelib($this->getFilelib());
            $fileItem->link = $file['link'] = $profile->getLinker()->getLink($fileItem, true);
            
            $this->getMongo()->files->update(array('_id' => $fileItem->_id), $file);
                       
            return $this->_addId($fileItem);

        } catch(Exception $e) {
            throw new Emerald_Filelib_Exception($e->getMessage());
        }
    	
    	
    }
    
    /**
     * Creates a folder
     *
     * @param Emerald_Filelib_FolderItem $folder
     * @return Emerald_Filelib_FolderItem Created folder
     * @throws Emerald_Filelib_Exception When fails
     */
    public function createFolder(Emerald_Filelib_FolderItem $folder)
    {
    	$arr = $folder->toArray();
    	$this->getMongo()->folders->insert($arr);
    	$this->getMongo()->folders->ensureIndex(array('name' => 1), array('unique' => true));
    	$folder->setFromArray($arr);
    	
    	return $this->_addId($folder);
    	
    }
    

    /**
     * Deletes a folder
     *
     * @param Emerald_Filelib_FolderItem $folder
     * @throws Emerald_Filelib_Exception When fails
     */
    public function deleteFolder(Emerald_Filelib_FolderItem $folder)
    {
        $this->getMongo()->folders->remove(array('_id' => $folder->_id));
    
    }
    
    /**
     * Deletes a file
     *
     * @param Emerald_Filelib_FileItem $file
     * @throws Emerald_Filelib_Exception When fails
     */
    public function deleteFile(Emerald_Filelib_FileItem $file)
    {
        $this->getMongo()->files->remove(array('_id' => $file->_id));
    }
    
    /**
     * Updates a folder
     *
     * @param Emerald_Filelib_FolderItem $folder
     * @throws Emerald_Filelib_Exception When fails
     */
    public function updateFolder(Emerald_Filelib_FolderItem $folder)
    {
        $this->_stripId($folder);
    	
    	$arr = $folder->toArray();
        
        $this->getMongo()->folders->update(array('_id' => $arr['_id']), $arr);
                        
        $folder->setFromArray($arr);
        
        return $this->_addId($folder);
        
        
        
    }
    
    /**
     * Updates a file
     *
     * @param Emerald_Filelib_FileItem $file
     * @throws Emerald_Filelib_Exception When fails
     */
    public function updateFile(Emerald_Filelib_FileItem $file)
    {
        $this->_stripId($file);
        
        $arr = $file->toArray();
        
        $this->getMongo()->files->update(array('_id' => $arr['_id']), $arr);
                        
        $file->setFromArray($arr);
        
        return $this->_addId($file);
        
    }
    

        
    /**
     * Finds the root folder
     *
     * @return Emerald_Filelib_FolderItem
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
        
        $className = $this->getFilelib()->getFolderItemClass();
        
        $item = new $className($root);
        
        return $this->_addId($item);
    }
    
    
    
    
    private function _addId($item)
    {
        $item->id = $item->_id->__toString();
        return $item;
    }
    
    
    private function _stripId($item)
    {
        unset($item->id);
    }
    
    
    
    
    
}