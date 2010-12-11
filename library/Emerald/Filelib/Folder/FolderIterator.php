<?php

namespace Emerald\Filelib\Folder;

/**
 * Folder item iterator
 *  
 * @author pekkis
 * @todo optimize via caching
 *
 */
class FolderIterator extends \Emerald\Filelib\AbstractIterator implements \RecursiveIterator
{

    /** 
     * Returns whether the current folder contains child folders
     * 
     */
    public function hasChildren()
    {
        $current = $this->current();
        return $current->getFilelib()->folder()->findSubFolders($current)->count();
        
    }

    /**
     * Returns the children of the current folder
     * 
     * @return \Emerald\Filelib\Folder\FolderIterator
     */
    public function getChildren()
    {
        $current = $this->current();
        return $current->getFilelib()->folder()->findSubFolders($current);
    }



}
