<?php
class EmAdmin_Form_SitemapCopy extends ZendX_JQuery_Form
{

	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . "/em-admin/sitemap/copy-from");
		
		$toElm = new Zend_Form_Element_Hidden('to');
		$toElm->setDecorators(array('ViewHelper'));
				
		$localeElm = new Zend_Form_Element_Select('from', array('label' => 'Locale'));
		$localeElm->setRegisterInArrayValidator(false);
		$localeElm->setRequired(false);
		$localeElm->setAllowEmpty(true);
		$localeElm->setIgnore(true);
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Copy'));
		$submitElm->setIgnore(true);
				
		$this->addElements(array($toElm, $localeElm, $submitElm));

	}
	
	
	
	public function setLocale($locale)
	{
		$localeModel = new EmCore_Model_Locale();
		$locales = $localeModel->findAll();
		
		$this->to->setValue($locale);
		
		foreach($locales as $l) {
			if($locale != $l) {
				$this->from->addMultiOption($l->locale, $l->locale);	
			}
		}
				
	}
	
	
	
}