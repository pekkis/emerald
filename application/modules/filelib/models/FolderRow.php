<?php
class Filelib_Model_FolderRow extends Zend_Db_Table_Row_Abstract implements Zend_Acl_Resource_Interface
{
	
	public function getResourceId()
	{
		return 'Emerald_Filelib_Folder_' . $this->id;
	}

	
	public function findParent()
	{
		if($this->parent_id) {
			return $this->findParentRow('Filelib_Model_DbTable_Folder');	
		}
		return false;
	}
	
}
?>