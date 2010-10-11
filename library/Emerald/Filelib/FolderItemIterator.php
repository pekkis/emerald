<?php

namespace Emerald\Filelib;

/**
 * Folder item iterator
 *  
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class FolderItemIterator extends AbstractItemIterator implements \RecursiveIterator
{

    public function hasChildren()
    {
        $current = $this->current();
        return $current->getFilelib()->folder()->findSubFolders($current)->count();
        
    }

    public function getChildren()
    {
        $current = $this->current();
        return $current->getFilelib()->folder()->findSubFolders($current);
    }



}
