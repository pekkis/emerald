<?php
class Admin_Form_PagePermissions extends ZendX_JQuery_Form
{

	public function init()
	{
		
		// $testElm = new Zend_Form_Element_Checkbox('check', array('label' => 'check'));
		// $this->addElements(array($testElm));		
		
		// $this->addElements(array($idElm, $localeElm, $parentIdElm, $layoutElm, $shardElm, $titleElm, $submitElm));

		
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
			
		
		
		//$this->set
		
		
		/*
		$this->view->selectedLocales = $selectedLocales;
		$this->view->availableLocales = $availableLocales;
		*/
		
	}
	
	
	
}