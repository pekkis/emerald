<?php
class EmAdmin_Form_PagePermissions extends ZendX_JQuery_Form
{

    public function init()
    {
        $this->setIsArray(true);

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
        	


        //$this->set


        /*
         $this->view->selectedLocales = $selectedLocales;
         $this->view->availableLocales = $availableLocales;
         */

    }



}