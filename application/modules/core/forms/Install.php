<?php
class Core_Form_Install extends ZendX_JQuery_Form
{
	
	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(URL_BASE . "/core/install/post");
		
		$emailElm = new Zend_Form_Element_Text('email', array('label' => 'E-mail', 'class' => 'w66'));
		$emailElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$emailElm->addValidator(new Zend_Validate_EmailAddress());
		$emailElm->setRequired(true);
		$emailElm->setAllowEmpty(false);
		
		$passwdElm = new Zend_Form_Element_Text('password', array('label' => 'Password', 'class' => 'w66'));
		$passwdElm->addValidator(new Zend_Validate_StringLength(4, 255));
		$passwdElm->setRequired(true);
		$passwdElm->setAllowEmpty(false);
						
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($emailElm, $passwdElm, $submitElm));
		
	}
}