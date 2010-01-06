<?php
class Admin_Form_User extends ZendX_JQuery_Form
{

	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction("/admin/user/save");
		$this->setAttrib('class', 'emerald-json');

		$idElm = new Zend_Form_Element_Hidden('id');
		$idElm->setDecorators(array('ViewHelper'));
						
		$emailElm = new Zend_Form_Element_Text('email', array('label' => 'E-mail address', 'class' => 'w66'));
		$emailElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$emailElm->addValidator(new Zend_Validate_EmailAddress());
		$emailElm->setRequired(true);
		$emailElm->setAllowEmpty(false);

		$firstnameElm = new Zend_Form_Element_Text('firstname', array('label' => 'Firstname', 'class' => 'w66'));
		$firstnameElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$firstnameElm->setRequired(false);
		$firstnameElm->setAllowEmpty(true);

		$lastnameElm = new Zend_Form_Element_Text('lastname', array('label' => 'Lastname', 'class' => 'w66'));
		$lastnameElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$lastnameElm->setRequired(false);
		$lastnameElm->setAllowEmpty(true);
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		$groupModel = new Core_Model_Group();
		$groups = $groupModel->findAll();
		
		$groupsElm = new Zend_Form_Element_MultiCheckbox('groups', array('Label' => 'Groups'));
		foreach($groups as $group) {
			$groupsElm->addMultiOption($group->id, $group->name);
		}		
		
		$this->addElements(array($idElm, $emailElm, $firstnameElm, $lastnameElm, $groupsElm, $submitElm));
		
		$pwdForm = new Admin_Form_UserPassword();
		
		$this->addSubForm($pwdForm, 'pwd', 5);

		
		
		
		
		
		
	}
	
	
}