<?php

namespace Emerald\Filelib\Backend\ZendDb;

/**
 * Folder row
 * 
 * @author pekkis
 * @package Emerald_Filelib
 *
 */
class FolderRow extends \Zend_Db_Table_Row_Abstract
{

    public function findParent()
    {
        if($this->parent_id) {
            return $this->findParentRow('\Emerald\Filelib\Backend\ZendDb\FolderTable');
        }
        return false;
    }

}
?>