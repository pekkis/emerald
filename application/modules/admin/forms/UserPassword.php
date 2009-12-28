<?php
class Admin_Form_UserPassword extends ZendX_JQuery_Form
{

	public function init()
	{
		
		$passwordElm = new Zend_Form_Element_Text('password', array('label' => 'Change password', 'class' => 'w66'));
		$passwordElm->addValidator(new Zend_Validate_StringLength(4, 255));
		$passwordElm->setRequired(false);
		$passwordElm->setAllowEmpty(true);
		
		$this->addElement($passwordElm);

	}
	
	
	
	
	
}