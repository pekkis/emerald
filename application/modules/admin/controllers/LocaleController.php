<?php
class Admin_LocaleController extends Emerald_Controller_Action 
{
	
	public $ajaxable = array(
		'update' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
	
	public function indexAction()
	{
		$this->view->form = new Admin_Form_Locale();		
	}
	
	
	public function updateAction()
	{
		$form = new Admin_Form_Locale();
		if(!$form->isValid($this->_getAllParams())) {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
		} else {
			
			$formLocales = array();
			if($form->locale->getValue()) {
				foreach($form->locale->getValue() as $key => $value) {
					$formLocales[] = $value;
				}
			}
			
			$localeModel = new Core_Model_Locale();
			
			$success = $localeModel->updateSiteLocales($formLocales);
			
			if($success) {
				$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');	
			} else {
				$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
			}
		}
			
		$this->view->message = $msg;
		
	}
	
	
}