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
		
		// Zend_Debug::dump($groups);
			

		$permissions = Emerald_Permission::getAll();
			
			// Zend_Debug::dump($permissions);
		
		foreach($groups as $group) {
			$elm = new Zend_Form_Element_MultiCheckbox($group->id, array('label' => $group->name));
			foreach($permissions as $key => $value) {
				$elm->addMultiOption($key, $value);
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