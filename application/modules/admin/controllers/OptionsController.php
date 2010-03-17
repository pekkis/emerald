<?php
class Admin_OptionsController extends Emerald_Controller_Action 
{
	
	public $ajaxable = array(
		'save-application' => array('json'),
		'save-locale' => array('json')
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
	
	
	public function indexAction()
	{
		$filters = array();
		$validators = array(
			'locale' => array(new Zend_Validate_Regex('([a-z]{2,3}(_[A-Z]{2})?)'), 'presence' => 'optional'),
		);
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$localeModel = new Core_Model_Locale();
			$locales = $localeModel->findAll();
												
			$appForm = new Admin_Form_ApplicationOptions();
			
			$customer = $this->getCustomer();
			
			$opts = $customer->getOptions();
			$appForm->setDefaults($opts);
			
			
			
			$this->view->appForm = $appForm;
				

			$this->view->localeForms = array();

			foreach($locales as $locale) {
				
				$form = new Admin_Form_LocaleOptions();
				
				$form->setDefaults($locale->getOptions());
				$form->locale->setValue($locale->locale);
				$form->setLocale($locale->locale);
				
				$this->view->localeForms[$locale->locale] = $form; 
				
			}
			
		
			
		} catch(Exception $e) {
			throw new Emerald_Exception($e->getMessage(), 500);
		}
		
		
		
		
		
	}
	
	
	public function saveApplicationAction()
	{
		$form = new Admin_Form_ApplicationOptions();
		if($form->isValid($this->_getAllParams())) {
			
			
			foreach($form->getValues() as $key => $value) {
				$this->getCustomer()->setOption($key, $value);
			}
			
			$this->view->message = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok');
			
			
		} else {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Save failed');
			$msg->errors = $form->getMessages();
			$this->view->message = $msg;
		}
		
		
	}
	
	
	public function saveLocaleAction()
	{
		$form = new Admin_Form_LocaleOptions();
		
		$form->setLocale($this->_getParam('locale'));
		if($form->isValid($this->_getAllParams())) {
			
			$localeModel = new Core_Model_Locale();
			$locale = $localeModel->find($form->locale->getValue());
			
			
			foreach($form->getValues() as $key => $value) {
				$locale->setOption($key, $value);
			}
			
			$this->view->message = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok');
			
			
		} else {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Save failed');
			$msg->errors = $form->getMessages();
			$this->view->message = $msg;
		}
		
	}
	
}