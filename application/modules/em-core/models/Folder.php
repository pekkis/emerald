<?php
class EmCore_Model_Folder extends Emerald_Cms_Model_AbstractModel
{


    public function getPermissions(EmCore_Model_FolderItem $folder)
    {

        $groupModel = new EmCore_Model_Group();
        $groups = $groupModel->findAll();

        $permissions = Emerald_Cms_Permission::getAll();

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



    public function savePermissions(EmCore_Model_FolderItem $folder, $permissions)
    {

        $tbl = new EmCore_Model_DbTable_Permission_Folder_Ugroup();
        $tbl->getAdapter()->beginTransaction();
        try {
            $tbl->delete($tbl->getAdapter()->quoteInto("folder_id = ?", $folder->getId()));
            if($permissions) {
                foreach($permissions as $key => $data) {
                    if($data) {
                        $sum = array_sum($data);
                        $tbl->insert(array('folder_id' => $folder->getId(), 'ugroup_id' => $key, 'permission' => $sum));
                    }
                }
            }
            $tbl->getAdapter()->commit();
            	

        } catch(Exception $e) {
            $tbl->getAdapter()->rollBack();
            return false;
        }



        $acl = Zend_Registry::get('Emerald_Acl');

        if($acl->has($folder)) {
            $acl->remove($folder);
        }


    }


}