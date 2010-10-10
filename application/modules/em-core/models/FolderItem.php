<?php
class EmCore_Model_FolderItem extends Emerald\Filelib\FolderItem implements Emerald_Common_Acl_ResourceInterface
{


    public function getResourceId()
    {
        return "Emerald_Filelib_Folder_{$this->getId()}";
    }


    public function autoloadAclResource(Zend_Acl $acl)
    {
        if(!$acl->has($this)) {
            	
            $acl->addResource($this);
            $model = new EmCore_Model_DbTable_Permission_Folder_Ugroup();
            $sql = "SELECT ugroup_id, permission FROM emerald_permission_folder_ugroup WHERE folder_id = ?";
            $res = $model->getAdapter()->fetchAll($sql, $this->getId());
             
            foreach($res as $row) {
                foreach(Emerald_Cms_Permission::getAll() as $key => $name) {
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