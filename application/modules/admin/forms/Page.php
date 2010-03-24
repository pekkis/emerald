<?php
class Admin_Form_Page extends ZendX_JQuery_Form
{

	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(URL_BASE . "/admin/page/save");

		$idElm = new Zend_Form_Element_Hidden('id');
		$idElm->setDecorators(array('ViewHelper'));
		
		$globalIdElm = new Zend_Form_Element_Text('global_id');
		$globalIdElm->setDecorators(array('ViewHelper'));
		
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
		foreach($layouts as $layout) {
			$layoutOpts[$layout] = $layout;
		}
		$layoutElm->setMultiOptions($layoutOpts);
		
				

		$shardElm = new Zend_Form_Element_Select('shard_id', array('label' => 'Shard id', 'class' => 'w66'));
		// $shardElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$shardElm->setRequired(true);
		$shardElm->setAllowEmpty(false);
		
		$shardModel = new Core_Model_Shard();
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
		
		$orderIdElm = new Zend_Form_Element_Text('order_id', array('label' => 'Weight'));
		$orderIdElm->addValidator(new Zend_Validate_Int(), new Zend_Validate_Between(1, 10000));
		$orderIdElm->setRequired(true);
		$orderIdElm->setAllowEmpty(false);
		// $orderIdElm->setValue(1);
		
		$visibleElm = new Zend_Form_Element_Checkbox('visibility', array('label' => 'Visible'));
		$visibleElm->addValidator(new Zend_Validate_InArray(array(0, 1)));	
		$visibleElm->setRequired(true);
		$visibleElm->setAllowEmpty(false);
		
		
		
		$interLocaleElm = new Zend_Form_Element_Select('interlink_locale');
		$interLocaleElm->setRequired(false);
		$interLocaleElm->setAllowEmpty(true);

		$localeModel = new Core_Model_Locale();
		$locales = $localeModel->findAll();
		
		$interLocaleElm->addMultiOption("", '--');
		foreach($locales as $l) {
			$interLocaleElm->addMultiOption($l->locale, $l->locale);
		}
		
		$interPageElm = new Zend_Form_Element_Select('interlink_page');
		$interLocaleElm->setRequired(false);
		$interLocaleElm->setAllowEmpty(true);

		
		
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		
		
		
		$this->addElements(array($idElm, $interLocaleElm, $interPageElm, $globalIdElm, $localeElm, $parentIdElm, $redirectIdElm, $layoutElm, $shardElm, $titleElm, $orderIdElm, $visibleElm, $submitElm));

		
		$permissionForm = new Admin_Form_PagePermissions();
		$permissionForm->setAttrib('id', 'page-permissions');
		
		
		$this->addSubForm($permissionForm, 'page-permissions', 10);

		
		
		
		
		
		
		/*
		$this->view->selectedLocales = $selectedLocales;
		$this->view->availableLocales = $availableLocales;
		*/
		
	}
	
	
	
	public function setLocale($locale)
	{
		$naviModel = new Core_Model_Navigation();
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
		
	}
	
	
	
}