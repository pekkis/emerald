<?php
class Admin_Form_Locale extends ZendX_JQuery_Form
{
	
	
	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction("/admin/locale/update/format/json");

		$localeElm = new Zend_Form_Element_MultiCheckbox('locale');
		
		$localeModel = new Core_Model_Locale();
		
		$availableLocales = $localeModel->getAvailableLocales();
		
		foreach($availableLocales as $al) {
			$localeElm->addMultiOption($al->locale, $al->locale);
		}
		
		$selectedLocalesRaw = $localeModel->findAll();
		$selectedLocales = array();
		foreach($selectedLocalesRaw as $localeRaw) {
			$selectedLocales[] = $localeRaw->locale;			
		}
		
		$localeElm->setValue($selectedLocales);
							
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($localeElm, $submitElm));
		

		
		
		
		

		

		/*
		$this->view->selectedLocales = $selectedLocales;
		$this->view->availableLocales = $availableLocales;
		*/
		
	}
	
	
	
	
}






?>


