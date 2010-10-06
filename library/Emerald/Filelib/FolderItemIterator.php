<?php

namespace Emerald\Filelib;

/**
 * Folder item iterator
 *  
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class FolderItemIterator extends \Emerald\Filelib\ItemIterator implements \RecursiveIterator
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
