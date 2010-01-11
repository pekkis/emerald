<?php
class Core_Model_Folder
{
	
	
	public function getPermissions(Core_Model_FolderItem $folder)
	{
				
		$groupModel = new Core_Model_Group();
		$groups = $groupModel->findAll();
				
		$permissions = Emerald_Permission::getAll();

		$perms = array();
		
		$acl = Zend_Registry::get('Emerald_Acl');
		
		foreach($groups as $group) {
			foreach($permissions as $permKey => $permName) {
				if($acl->isAllowed($group, $folder, $permName)) {
					$perms[$group->id][] = $permKey;
				}	
			}
		}
						
		
		return $perms;
	}
	
	
	
	public function savePermissions(Core_Model_FolderItem $folder, $permissions)
	{
		$tbl = new Core_Model_DbTable_Permission_Folder_Ugroup();
		$tbl->getAdapter()->beginTransaction();
		try {
			$tbl->delete($tbl->getAdapter()->quoteInto("folder_id = ?", $folder->id));
			if($permissions) {
				foreach($permissions as $key => $data) {
					if($data) {
						$sum = array_sum($data);
						$tbl->insert(array('folder_id' => $folder->id, 'ugroup_id' => $key, 'permission' => $sum));
					}
				}
			}
			$tbl->getAdapter()->commit();
			return true;
		} catch(Exception $e) {
			$tbl->getAdapter()->rollBack();
			return false;
		}
	}
	
	
}