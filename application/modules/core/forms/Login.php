<?php
class Core_Form_Login extends ZendX_JQuery_Form
{
	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . "/core/user/login");
		
		$emailElm = new Zend_Form_Element_Text('tussi', array('label' => 'E-mail', 'class' => 'w66'));
		$emailElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$emailElm->setRequired(true);
		$emailElm->setAllowEmpty(false);
		
		$passwdElm = new Zend_Form_Element_Password('loso', array('label' => 'Password', 'class' => 'w66'));
		$passwdElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$passwdElm->setRequired(true);
		$passwdElm->setAllowEmpty(true);
						
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($emailElm, $passwdElm, $submitElm));
		
	}
	
		
}