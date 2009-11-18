<?php
class Emerald_Filelib_FolderRow extends Zend_Db_Table_Row_Abstract implements Zend_Acl_Resource_Interface
{
	
	public function getResourceId()
	{
		return 'Emerald_Filelib_Folder_' . $this->id;
	}

	
	public function findParent()
	{
		if($this->parent_id) {
			return $this->findParentRow('Emerald_Filelib_DbTable_Folder');	
		}
		return false;
	}
	
}
?>