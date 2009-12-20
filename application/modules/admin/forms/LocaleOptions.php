<?php
class Admin_Form_LocaleOptions extends ZendX_JQuery_Form
{
	
	
	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction("/admin/options/save-locale/format/json");

		$localeElm = new Zend_Form_Element_Hidden('locale');
		$localeElm->setDecorators(array('ViewHelper'));
		$localeElm->setIgnore(true);
		
		$titleElm = new Zend_Form_Element_Text('title', array('label' => 'Browser title', 'class' => 'w66'));
		$titleElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$titleElm->setRequired(false);
		$titleElm->setAllowEmpty(true);
		
		
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($localeElm, $titleElm, $submitElm));
		
	}
	
	
	
	
}






?>

