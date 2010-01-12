<?php
class Admin_Form_Page extends ZendX_JQuery_Form
{

	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction("/admin/page/save/format/json");

		$idElm = new Zend_Form_Element_Hidden('id');
		$idElm->setDecorators(array('ViewHelper'));
				
		$localeElm = new Zend_Form_Element_Hidden('locale');
		$localeElm->setDecorators(array('ViewHelper'));
				
		$parentIdElm = new Zend_Form_Element_Select('parent_id', array('label' => 'Parent'));
		// $parentIdElm->setAutoInsertNotEmptyValidator(false);
		$parentIdElm->setRequired(false);
		$parentIdElm->setAllowEmpty(true);
		
		$layoutElm = new Zend_Form_Element_Select('layout', array('label' => 'Layout', 'class' => 'w66'));
		// $layoutElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$layoutElm->setRequired(false);
		$layoutElm->setAllowEmpty(true);
		
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
			}
		}
		$shardElm->setMultiOptions($shardOpts);
		
		
		$titleElm = new Zend_Form_Element_Text('title', array('label' => 'Page title', 'class' => 'w66'));
		$titleElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$titleElm->setRequired(false);
		$titleElm->setAllowEmpty(true);
				
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($idElm, $localeElm, $parentIdElm, $layoutElm, $shardElm, $titleElm, $submitElm));

		
		$permissionForm = new Admin_Form_PagePermissions();
		$permissionForm->setAttrib('id', 'page-permissions');
		
		
		$this->addSubForm($permissionForm, 'page-permissions', 6);

		
		
		
		
		
		
		/*
		$this->view->selectedLocales = $selectedLocales;
		$this->view->availableLocales = $availableLocales;
		*/
		
	}
	
	
	
	public function setLocale($locale)
	{
		$naviModel = new Core_Model_Navigation();
		$navi = $naviModel->getNavigation();
						
		$navi = $navi->findBy("uri", "/" . $locale);

		$iter = new RecursiveIteratorIterator($navi, RecursiveIteratorIterator::SELF_FIRST);
		
		$opts = array();
		
		$opts[$locale] = $locale; 
		
		foreach($iter as $navi) {
			$opts[$navi->id] = str_repeat("-", $iter->getDepth() + 1) . $navi->label;
		}
		
		$this->parent_id->setMultiOptions($opts);
		$this->locale->setValue($locale);
		
	}
	
	
	
}