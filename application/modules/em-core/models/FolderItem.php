<?php
class EmCore_Model_FolderItem extends Emerald_Filelib_FolderItem implements Emerald_Acl_Resource_Interface
{


    public function getResourceId()
    {
        return "Emerald_Filelib_Folder_{$this->id}";
    }


    public function autoloadAclResource(Zend_Acl $acl)
    {
        	
        if(!$acl->has($this)) {
            	
            $acl->add($this);
            $model = new EmCore_Model_DbTable_Permission_Folder_Ugroup();
            $sql = "SELECT ugroup_id, permission FROM emerald_permission_folder_ugroup WHERE folder_id = ?";
            $res = $model->getAdapter()->fetchAll($sql, $this->id);
             
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