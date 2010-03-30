<?php
class EmCore_Form_FormContent extends ZendX_JQuery_Form
{

	public function init()
	{
		$this->setMethod(Zend_Form::METHOD_POST);
		$this->setAction(EMERALD_URL_BASE . "/em-core/form-content/save");
				
		$pageIdElm = new Zend_Form_Element_Hidden('page_id');		
		$pageIdElm->setDecorators(array('ViewHelper'));

		$formIdElm = new Zend_Form_Element_Select('form_id', array('label' => 'Form', 'class' => 'w33'));
		
		$formModel = new EmCore_Model_Form();
		$forms = $formModel->findAll();
		
		$opts = array();
		foreach($forms as $form) {
			$opts[$form->id] = $form->name;
		}
		
		$formIdElm->setMultiOptions($opts);
				
		$subjectElm = new Zend_Form_Element_Text('email_subject', array('label' => 'Email subject'));
		$subjectElm->addValidator(new Zend_Validate_StringLength(0, 255));
		$subjectElm->setRequired(true);
		$subjectElm->setAllowEmpty(false);
		
		$senderElm = new Zend_Form_Element_Text('email_from', array('label' => 'Email from'));
		$senderElm->addValidator(new Zend_Validate_EmailAddress());
		$senderElm->setRequired(true);
		$senderElm->setAllowEmpty(false);
		
		$receiverElm = new Zend_Form_Element_Text('email_to', array('label' => 'Email to'));
		$receiverElm->addValidator(new Zend_Validate_EmailAddress());
		$receiverElm->setRequired(true);
		$receiverElm->setAllowEmpty(false);

		$redirectElm = new Zend_Form_Element_Select('redirect_page_id', array('label' => 'Redirect to'));
		$redirectElm->setRequired(true);
		$redirectElm->setAllowEmpty(false);
		
		$submitElm = new Zend_Form_Element_Submit('submit', array('label' => 'Save'));
		
		$this->addElements(array($pageIdElm, $formIdElm, $subjectElm, $senderElm, $receiverElm, $redirectElm, $submitElm));
				
	}
	
	
	
	public function setLocale($locale)
	{
		$naviModel = new EmCore_Model_Navigation();
		$navi = $naviModel->getNavigation();
						
		$navi = $navi->findBy("uri", EMERALD_URL_BASE . "/" . $locale);

		$iter = new RecursiveIteratorIterator($navi, RecursiveIteratorIterator::SELF_FIRST);
		
		$opts = array();
		
		$opts[$locale] = $locale; 
		
		foreach($iter as $navi) {
			$opts[(string) $navi->id] = str_repeat("-", $iter->getDepth() + 1) . $navi->label;
		}
		
		
				
		$this->redirect_page_id->setMultiOptions($opts);
		
		// $this->redirect_page_id->addValidator(new Zend_Validate_InArray(array_keys($opts)));
		
				
		
	}
	
		
	
}
