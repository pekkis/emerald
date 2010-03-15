<?php
class Admin_LocaleController extends Emerald_Controller_Action 
{
	
	public $ajaxable = array(
		'update' => array('json'),
		'delete' => array('json'),
		'save' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
	
	public function indexAction()
	{
		$localeModel = new Core_Model_Locale();

		$this->view->locales = $localeModel->findAll();
				 
		$this->view->form = new Admin_Form_LocaleAdd();		
	}
	
	
	public function updateAction()
	{
		$form = new Admin_Form_LocaleAdd();
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
	
	
	public function editAction()
	{
		$localeModel = new Core_Model_Locale();

		$locale = $localeModel->find($this->_getParam('id'));		
		
		$form = new Admin_Form_Locale();
		$form->setDefaults($locale->toArray());

		$permForm = $form->getSubForm('locale-permissions');
		$permissions = $localeModel->getPermissions($locale);
		$permForm->setDefaults($permissions);
				
		$this->view->form = $form;
				
		
	}
	
	
	public function deleteAction()
	{
		$localeModel = new Core_Model_Locale();
		$locale = $localeModel->find($this->_getParam('id'));
		
		try {
			$localeModel->delete($locale);
			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Delete ok');	
		} catch(Exception $e) {
			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Delete failed');
		}
		
	}
	
	
	
	public function saveAction()
	{

		$localeModel = new Core_Model_Locale();
		
		$form = new Admin_Form_Locale();
		
		$locale = $localeModel->find($this->_getParam('locale'));

		if(!$locale) {
			throw new Emerald_Exception('Locale not found', 404);
		}
		
		if(!$form->isValid($this->_getAllParams())) {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Epic fail');
			$msg->errors = $form->getMessages(); 
		} else {
			$locale->setFromArray($form->getValues());
			$localeModel->save($locale, $form->getSubForm('locale-permissions')->getValues());
			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Ok');
			$msg->locale_id = $locale->id;
		}
		
		$this->view->message = $msg;
				
		
	}
	
	
	
	
}