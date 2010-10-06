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
        return $this->current()->findSubFolders()->count();
    }

    public function getChildren()
    {
        return $this->current()->findSubFolders();
    }



}
