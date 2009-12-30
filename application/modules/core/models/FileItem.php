<?php
class Core_Model_FileItem extends Emerald_Filelib_FileItem implements Emerald_Acl_Resource_Interface
{

	
	public function getResourceId()
	{
		return "Emerald_Filelib_FileItem_{$this->id}";
	} 
	
	
	
	public function __lazyLoadAclResource(Zend_Acl $acl)
	{
		if(!$acl->has($this)) {
			$acl->add($this);
			$model = new Core_Model_DbTable_Permission_Folder_Ugroup();
	       	$sql = "SELECT ugroup_id, permission FROM permission_folder_ugroup WHERE folder_id = ?";
	       	$res = $model->getAdapter()->fetchAll($sql, $this->folder_id);
	       	foreach($res as $row) {
	       		foreach(Emerald_Permission::getAll() as $key => $name) {
	       			if($key & $row->permission) {
	       				$role = "Emerald_Group_{$row->ugroup_id}";
	       				if($acl->hasRole($role)) {
	       					$acl->allow($role, $this, $name);	
	       				}
	       			}
	       		}
	       	}
		}		
		
	}
	

}