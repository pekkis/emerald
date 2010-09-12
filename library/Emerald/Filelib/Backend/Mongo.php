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
        die('xooxer');
    }
    

    /**
     * Finds a file
     *
     * @param integer $id
     * @return Emerald_Filelib_FileItem|false
     */
    public function findFile($id)
    {
        die('xooxer1');
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
            $file = new $fileItemClass($row->toArray());
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
        die('xooxer3');
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
        die('xooxer4');
    }
    

    /**
     * Deletes a folder
     *
     * @param Emerald_Filelib_FolderItem $folder
     * @throws Emerald_Filelib_Exception When fails
     */
    public function deleteFolder(Emerald_Filelib_FolderItem $folder)
    {
        die('xooxer5');
    }
    
    /**
     * Deletes a file
     *
     * @param Emerald_Filelib_FileItem $file
     * @throws Emerald_Filelib_Exception When fails
     */
    public function deleteFile(Emerald_Filelib_FileItem $file)
    {
        die('xooxer6');
    }
    
    /**
     * Updates a folder
     *
     * @param Emerald_Filelib_FolderItem $folder
     * @throws Emerald_Filelib_Exception When fails
     */
    public function updateFolder(Emerald_Filelib_FolderItem $folder)
    {
        die('xooxer7');
    }
    
    /**
     * Updates a file
     *
     * @param Emerald_Filelib_FileItem $file
     * @throws Emerald_Filelib_Exception When fails
     */
    public function updateFile(Emerald_Filelib_FileItem $file)
    {
        die('xooxer8');
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
            
            $mongo->folders->save($arr);
                        
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