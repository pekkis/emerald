<?php
class EmAdmin_Form_FolderPermissions extends ZendX_JQuery_Form
{

    public function init()
    {
        $groupModel = new EmCore_Model_Group();
        $groups = $groupModel->findAll();

        $permissions = Emerald_Cms_Permission::getAll();

        foreach($groups as $group) {
            $elm = new Zend_Form_Element_MultiCheckbox((string) $group->id, array('label' => $group->name));
            foreach($permissions as $key => $value) {
                $elm->addMultiOption((string) $key, $value);
            }
            $this->addElement($elm);
        }


    }



}