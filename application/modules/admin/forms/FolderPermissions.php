<?php
class Admin_Form_FolderPermissions extends ZendX_JQuery_Form
{

	public function init()
	{
		$groupModel = new Core_Model_Group();
		$groups = $groupModel->findAll();

		$permissions = Emerald_Permission::getAll();
		
		foreach($groups as $group) {
			$elm = new Zend_Form_Element_MultiCheckbox((string) $group->id, array('label' => $group->name));
			foreach($permissions as $key => $value) {
				$elm->addMultiOption((string) $key, $value);
			}
			$this->addElement($elm);
		}
	
		
	}
	
	
	
}