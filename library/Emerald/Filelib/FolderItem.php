<?php

namespace Emerald\Filelib;

/**
 * Folder item
 *
 * @package Emerald_Filelib
 * @author pekkis
 *
 */
class FolderItem extends AbstractItem
{

    /**
     * @var Emerald\Filelib\FileLibrary Filelib
     */
    private $_filelib;

    /**
     * Sets filelib
     *
     * @param Emerald_Filelib $filelib
     */
    public function setFilelib(Emerald\Filelib\FileLibrary $filelib)
    {
        $this->_filelib = $filelib;
    }

    /**
     * Returns filelib
     *
     * @return Emerald\Filelib\FileLibrary Filelib
     */
    public function getFilelib()
    {
        return $this->_filelib;
    }


    /**
     * Returns files in folder
     *
     * @return Emerald\Filelib\FileItemIterator
     */
    public function findFiles()
    {
        return $this->getFilelib()->folder()->findFiles($this);
    }

    /**
     * Returns parent folder
     *
     * @return Emerald\Filelib\FolderItem|false
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
     * @return Emerald\Filelib\FolderItemIterator
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
