<?php
/**
 * Folder item
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class Emerald_Filelib_FolderItem extends Emerald_Model_AbstractItem
{

    /**
     * @var Emerald_Filelib Filelib
     */
    private $_filelib;

    /**
     * Sets filelib
     *
     * @param Emerald_Filelib $filelib
     */
    public function setFilelib(Emerald_Filelib $filelib)
    {
        $this->_filelib = $filelib;
    }

    /**
     * Returns filelib
     *
     * @return Emerald_Filelib Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }


    /**
     * Returns files in folder
     *
     * @return Emerald_Filelib_FileItemIterator
     */
    public function findFiles()
    {
        return $this->getFilelib()->folder()->findFiles($this);
    }

    /**
     * Returns parent folder
     *
     * @return Emerald_Filelib_FolderItem|false
     */
    public function findParent()
    {
        if($this->parent_id) {
            return $this->getFilelib()->folder()->find($this->parent_id);
        }
        return false;
    }

    /**
     * Finds subfolders
     *
     * @return Emerald_Filelib_FolderItemIterator
     */
    public function findSubFolders()
    {
        return $this->getFilelib()->folder()->findSubFolders($this);
    }


    public function __sleep()
    {
        return array('_enforceFieldIntegrity', '_data');
    }


}
