<?php
class Emerald_Db_Table_Row_Filelib_Folder extends Zend_Db_Table_Row_Abstract implements Zend_Acl_Resource_Interface
{

	
	public function init()
	{
		$acl = Zend_Registry::get('Emerald_Acl');
		if(!$acl->has($this)) {
			$db = $this->_getTable()->getAdapter();
			$acl->add($this);
	       	$sql = "SELECT ugroup_id, permission FROM permission_filelib_folder_ugroup WHERE folder_id = ?";
	       	$res = $db->fetchAll($sql, $this->id);
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
	
	
	public function getParent()
	{
		return $this->getTable()->find($this->parent_id)->current();
	}
	
	
	public function getFiles()
	{
		$fileTbl = Emerald_Model::get('Filelib_File');
		return $fileTbl->fetchAll(array('folder_id = ?' => $this->id));
	}
	
	
	public function getResourceId()
	{
		return 'Emerald_Filelib_Folder_' . $this->id;
	}
	
	
	public function assertWritable(Emerald_User $user = null)
	{
		if(!$user)
			$user = Zend_Registry::get('Emerald_User');
			
		if(!Zend_Registry::get('Emerald_Acl')->isAllowed($user, $this, 'write'))
			throw new Emerald_Acl_ForbiddenException('Forbidden', 401);
		
	}
	
	
	public function assertReadable(Emerald_User $user = null)
	{
		if(!$user)
			$user = Zend_Registry::get('Emerald_User');
		
		if(!Zend_Registry::get('Emerald_Acl')->isAllowed($user, $this, 'read'))
			throw new Emerald_Acl_ForbiddenException('Forbidden', 401);
			
	}
		
	

}
?>