<?php
class Admin_LocaleController extends Emerald_Controller_AdminAction 
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
		if(!$this->getCurrentUser()->inGroup(Core_Model_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		

		$this->view->form = new Admin_Form_Locale();		
		
		
	}
	
	
	/**
	 * This is The Most Dangerous Action Alive(tm). User can wipe out all data from all the pages
	 * of all the locales with a click of a button. Thank you cascading deletes! :)
	 * 
	 * @todo Implement ACL, only root can do. 
	 * @todo Maybe change the cascading in Locale table to restrict?
	 * @todo Maybe implement status for locale, just update it until janitor deletes from db?!?
	 *
	 */
	public function updateAction()
	{
		if(!$this->getCurrentUser()->inGroup(Core_Model_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		
		
		$form = new Admin_Form_Locale();
		if(!$form->isValid($this->_getAllParams())) {
			

			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
			
			
			
		} else {
			
			$formLocales = array();
			foreach($form->locale->getValue() as $key => $value) {
				$formLocales[] = $value;
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