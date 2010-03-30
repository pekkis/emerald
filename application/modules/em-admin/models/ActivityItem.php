<?php
class EmAdmin_Model_ActivityItem extends Emerald_Model_AbstractItem implements Emerald_Acl_Resource_Interface
{
	
	
	public function getResourceId()
	{
		return 'Emerald_Activity_' . $this->id;
	}
		
	
	
	public function autoloadAclResource(Zend_Acl $acl)
	{
		
		if(!$acl->has($this)) {
			$acl->addResource($this);
			$model = new EmCore_Model_DbTable_Permission_Page_Ugroup();
	       	$sql = "SELECT ugroup_id FROM emerald_permission_activity_ugroup WHERE activity_id = ?";
	       	$res = $model->getAdapter()->fetchAll($sql, $this->id);
	       	foreach($res as $row) {
				$role = "Emerald_Group_{$row->ugroup_id}";
   				
				if(!$acl->hasRole($role)) {
					$acl->addRole($role);
				}
				
				$acl->allow($role, $this);	
   			}
		}		
	}
	
	
	
	
}
