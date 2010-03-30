<?php
class EmAdmin_Form_LocaleOptions extends ZendX_JQuery_Form
{
	
	
	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . "/admin/options/save-locale/format/json");

		$localeElm = new Zend_Form_Element_Hidden('locale');
		$localeElm->setDecorators(array('ViewHelper'));
		$localeElm->setIgnore(true);
		
		$titleElm = new Zend_Form_Element_Text('title', array('label' => 'Browser title', 'class' => 'w66'));
		$titleElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$titleElm->setRequired(false);
		$titleElm->setAllowEmpty(true);
		
		$startPageElm = new Zend_Form_Element_Select('page_start', array('label' => 'Homepage'));
		$startPageElm->setRequired(true);
		$startPageElm->setAllowEmpty(false);
						
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($localeElm, $titleElm, $startPageElm, $submitElm));
		
	}
	
	
	
	public function setLocale($locale)
	{
		$naviModel = new EmCore_Model_Navigation();
		$navi = $naviModel->getNavigation();
		$navi = $navi->findBy("locale_root", $locale);
		$iter = new RecursiveIteratorIterator($navi, RecursiveIteratorIterator::SELF_FIRST);
		$opts = array();
		$opts[''] = $locale; 
		foreach($iter as $navi) {
			$opts[$navi->id] = str_repeat("-", $iter->getDepth() + 1) . $navi->label;
		}
		$this->page_start->setMultiOptions($opts);
		
		
	}
	
	
	
	
}






?>

