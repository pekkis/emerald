<?php
class Admin_Form_LocaleAdd extends ZendX_JQuery_Form
{
	
	
	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . "/admin/locale/update/format/json");

		$localeElm = new Zend_Form_Element_MultiCheckbox('locale');
		
		$localeModel = new Core_Model_Locale();
		
		$availableLocales = $localeModel->getAvailableLocales();
		

		$selectedLocalesRaw = $localeModel->findAll();
		$selectedLocales = array();
		foreach($selectedLocalesRaw as $localeRaw) {
			$selectedLocales[] = $localeRaw->locale;			
		}
				
		foreach($availableLocales as $al) {
			if(!in_array($al->locale, $selectedLocales)) {
				$localeElm->addMultiOption($al->locale, $al->locale);	
			}
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


