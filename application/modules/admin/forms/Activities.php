<?php
class Admin_Form_Activities extends ZendX_JQuery_Form
{
	
	
	public function init()
	{
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . "/admin/activity/save");
			
		
		$groupModel = new Core_Model_Group();
		$groups = $groupModel->findAll();

		$activityModel = new Admin_Model_Activity();
		
		$activities = $activityModel->getActivitiesByCategory();
		
		foreach($activities as $category => $acts) {
			
			$fieldset = array();

			foreach($acts as $a) {
				$elm = new Zend_Form_Element_MultiCheckbox((string) $a->id, array('label' => $a->name));
				// $elm->setIsArray(true);
				
				foreach($groups as $group) {
					$elm->addMultiOption((string) $group->id, $group->name);
				}

				$permissions = $activityModel->getPermissions($a);
				$elm->setValue($permissions);
				
				
				
				$this->addElement($elm);
				
				$fieldset[] = (string) $a->id;
			}
						
			$this->addDisplayGroup($fieldset, $category);
						
			
		}
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($submitElm));
				
	}
	
	
	
}
