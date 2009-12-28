<?php
class Admin_Form_User extends ZendX_JQuery_Form
{

	public function init()
	{
				
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction("/admin/page/save/format/json");

		$idElm = new Zend_Form_Element_Hidden('id');
		$idElm->setDecorators(array('ViewHelper'));
				
		
		$emailElm = new Zend_Form_Element_Text('email', array('label' => 'E-mail address', 'class' => 'w66'));
		$emailElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$emailElm->addValidator(new Zend_Validate_EmailAddress());
		$emailElm->setRequired(true);
		$emailElm->setAllowEmpty(false);

		$firstnameElm = new Zend_Form_Element_Text('firstname', array('label' => 'Firstname', 'class' => 'w66'));
		$firstnameElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$firstnameElm->setRequired(false);
		$firstnameElm->setAllowEmpty(true);

		$lastnameElm = new Zend_Form_Element_Text('lastname', array('label' => 'Lastname', 'class' => 'w66'));
		$lastnameElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$lastnameElm->setRequired(false);
		$lastnameElm->setAllowEmpty(true);
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		$submitElm->setIgnore(true);
		
		$this->addElements(array($idElm, $emailElm, $firstnameElm, $lastnameElm, $submitElm));

		
		$pwdForm = new Admin_Form_UserPassword();
		
		$this->addSubForm($pwdForm, 'pwd', 6);

		
		
		
		
		
		
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