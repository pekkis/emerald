<?php
class EmAdmin_Form_Page extends Zend_Form
{

	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . "/em-admin/page/save");

		$idElm = new Zend_Form_Element_Hidden('id');
		$idElm->setDecorators(array('ViewHelper'));
		
		$globalIdElm = new Zend_Form_Element_Text('global_id', array('label' => 'Current global id'));
		// $globalIdElm->setDecorators(array('ViewHelper'));
		
		$localeElm = new Zend_Form_Element_Hidden('locale');
		$localeElm->setDecorators(array('ViewHelper'));
				
		$parentIdElm = new Zend_Form_Element_Select('parent_id', array('label' => 'Parent'));
		// $parentIdElm->setAutoInsertNotEmptyValidator(false);
		$parentIdElm->setRequired(false);
		$parentIdElm->setAllowEmpty(true);
		
		$redirectIdElm = new Zend_Form_Element_Select('redirect_id', array('label' => 'Redirect to'));
		// $redirectIdElm->setAutoInsertNotEmptyValidator(false);
		$redirectIdElm->setRequired(false);
		$redirectIdElm->setAllowEmpty(true);
				
						
		$layoutElm = new Zend_Form_Element_Select('layout', array('label' => 'Layout', 'class' => 'w66'));
		// $layoutElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$layoutElm->setRequired(true);
		$layoutElm->setAllowEmpty(false);
		
		$layouts = Zend_Registry::get('Emerald_Customer')->getLayouts();
		
		
		$layoutOpts = array();
		foreach($layouts as $key => $layout) {
			
			if($key != 'Error') {
				$layoutOpts[$key] = $layout->getDescription();	
			}
			
		}
		$layoutElm->setMultiOptions($layoutOpts);
		
				

		$shardElm = new Zend_Form_Element_Select('shard_id', array('label' => 'Page type', 'class' => 'w66'));
		// $shardElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$shardElm->setRequired(true);
		$shardElm->setAllowEmpty(false);
		
		$shardModel = new EmCore_Model_Shard();
		$shards = $shardModel->findAll();
		
		$shardOpts = array();
		foreach($shards as $shard) {
			if($shard->isInsertable()) {
				$shardOpts[$shard->id] = $shard->name;
				
				/*
				if($shard->name == 'Html') {
					$shardElm->setValue($shard->id);
				}
				*/				
			}
		}
		$shardElm->setMultiOptions($shardOpts);
		
		
		$titleElm = new Zend_Form_Element_Text('title', array('label' => 'Page title', 'class' => 'w66'));
		$titleElm->addValidator(new Zend_Validate_StringLength(1, 255));
		$titleElm->setRequired(true);
		$titleElm->setAllowEmpty(false);

		$customUrlElm = new Zend_Form_Element_Text('customurl', array('label' => 'Custom link', 'class' => 'w66'));
		$customUrlElm->addValidator(new Zend_Validate_StringLength(1, 255));
		$customUrlElm->setRequired(false);
		$customUrlElm->setAllowEmpty(true);

		$classElm = new Zend_Form_Element_Text('class_css', array('label' => 'Class', 'class' => 'w66'));
		$classElm->addFilter(new Zend_Filter_Null());
		$classElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$classElm->setRequired(false);
		$classElm->setAllowEmpty(true);
				
		$orderIdElm = new Zend_Form_Element_Text('order_id', array('label' => 'Weight'));
		$orderIdElm->addValidator(new Zend_Validate_Int(), new Zend_Validate_Between(1, 10000));
		$orderIdElm->setRequired(true);
		$orderIdElm->setAllowEmpty(false);
		// $orderIdElm->setValue(1);
		
		$visibleElm = new Zend_Form_Element_Checkbox('visibility', array('label' => 'Visible'));
		$visibleElm->addValidator(new Zend_Validate_InArray(array(0, 1)));	
		$visibleElm->setRequired(true);
		$visibleElm->setAllowEmpty(false);
		
		
		
		$interLocaleElm = new Zend_Form_Element_Select('interlink_locale', array('label' => 'Locale'));
		$interLocaleElm->setRegisterInArrayValidator(false);
		$interLocaleElm->setRequired(false);
		$interLocaleElm->setAllowEmpty(true);
		$interLocaleElm->setIgnore(true);
		
		$interPageElm = new Zend_Form_Element_Select('interlink_page', array('label' => 'Page'));
		$interPageElm->setRegisterInArrayValidator(false);
		$interPageElm->setRequired(false);
		$interPageElm->setAllowEmpty(true);
		$interPageElm->setIgnore(true);

		
		$mirrorElm = new Zend_Form_Element_Checkbox('mirror', array('label' => 'Mirror changes where applicable'));
		$mirrorElm->addValidator(new Zend_Validate_InArray(array(0, 1)));	
		$mirrorElm->setRequired(false);
		$mirrorElm->setAllowEmpty(false);
		$mirrorElm->setIgnore(true);
				
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		
		$this->addElements(array($idElm, $interLocaleElm, $interPageElm, $globalIdElm, $localeElm, $parentIdElm, $redirectIdElm, $layoutElm, $shardElm, $titleElm, $customUrlElm, $classElm, $orderIdElm, $visibleElm, $mirrorElm, $submitElm));

		
		$permissionForm = new EmAdmin_Form_PagePermissions();
		$permissionForm->setAttrib('id', 'page-permissions');
		
		
		$this->addSubForm($permissionForm, 'page-permissions', 11);

		$taggableModel = new EmCore_Model_Taggable();
		$tagForm = $taggableModel->getForm();
		$this->addSubForm($tagForm, 'tags', 12);
		
		
		$this->addDisplayGroup(array('global_id', 'interlink_locale', 'interlink_page', 'mirror'), 'interlink', array('legend' => 'Interlinking', 'order' => 13));

		
		
		
		/*
		$this->view->selectedLocales = $selectedLocales;
		$this->view->availableLocales = $availableLocales;
		*/
		
	}
	
	
	
	public function setLocale($locale)
	{
		$naviModel = new EmCore_Model_Navigation();
		$navi = $naviModel->getNavigation();
						
		$navi = $navi->findBy("locale_root", $locale);
		$iter = new RecursiveIteratorIterator($navi, RecursiveIteratorIterator::SELF_FIRST);
		
		$opts = array();
		$opts[$locale] = $locale; 

		$ropts = array();
		$ropts[''] = 'No redirect';
				
		foreach($iter as $navi) {
			$ropts[$navi->id] = $opts[$navi->id] = str_repeat("-", $iter->getDepth() + 1) . $navi->label;
		}
		
		$this->parent_id->setMultiOptions($opts);
		$this->redirect_id->setMultiOptions($ropts);
		
		$this->redirect_id->setMultiOptions($ropts);
		$this->locale->setValue($locale);

		
		$localeModel = new EmCore_Model_Locale();
		$locales = $localeModel->findAll();
		
		$this->interlink_locale->addMultiOption("", '--');
		foreach($locales as $l) {
			if($l != $locale) {
				$this->interlink_locale->addMultiOption($l->locale, $l->locale);	
			}
		}
		
		
	}
	
	
	
}