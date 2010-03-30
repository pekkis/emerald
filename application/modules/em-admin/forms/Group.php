<?php
class EmAdmin_Form_Group extends ZendX_JQuery_Form
{

	public function init()
	{
				
		
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . "/em-admin/group/save");
		$this->setAttrib('class', 'emerald-json');

		$idElm = new Zend_Form_Element_Hidden('id');
		$idElm->setDecorators(array('ViewHelper'));
						
		$nameElm = new Zend_Form_Element_Text('name', array('label' => 'Name', 'class' => 'w66'));
		$nameElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$nameElm->setRequired(true);
		$nameElm->setAllowEmpty(false);
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);

		/*
		$groupModel = new EmCore_Model_Group();
		$groups = $groupModel->findAll();
		
		$groupsElm = new Zend_Form_Element_MultiCheckbox('groups', array('Label' => 'Groups'));
		foreach($groups as $group) {
			$groupsElm->addMultiOption($group->id, $group->name);
		}		
		*/
		
		$this->addElements(array($idElm, $nameElm, $submitElm));
		
		
	}
	
	
}