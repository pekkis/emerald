<?php
class Admin_OptionsController extends Emerald_Controller_AdminAction 
{
	public function indexAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		
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
			
			$opts = $customer->getOptionContainer()->getOptions();
			$appForm->setDefaults($opts);
			
			
			
			$this->view->appForm = $appForm;
				
			
			
		
			
		} catch(Exception $e) {
			throw new Emerald_Exception($e->getMessage(), 500);
		}
		
		
		
		
		
	}
	
	
	public function saveApplicationAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$filters = array();
		$validators = array(
			'default_locale' => array(new Zend_Validate_Regex('([a-z]{2,3}(_[A-Z]{2})?)'), 'presence' => 'optional', 'allowEmpty' => true),
			'google_analytics_id' => array(new Zend_Validate_StringLength(1, 20), 'presence' => 'optional', 'allowEmpty' => true),
		);
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			
			$application = Zend_Registry::get('Emerald_Customer');
			$application->setOption('default_locale', $input->default_locale);
			$application->setOption('google_analytics_id', $input->google_analytics_id);
				
			
			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'l:common/save_ok');
			
			
		} catch(Exception $e) {
			
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'l:common/save_failed');
			$msg->errorFields = array_keys($input->getMessages());
		}
		
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($msg);
		
		
		
		
	}
	
	
	public function saveLocaleAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$filters = array();
		$validators = array(
			'locale' => array(new Zend_Validate_Regex('([a-z]{2,3}(_[A-Z]{2})?)'), 'presence' => 'required'), 
			'title' => array(new Zend_Validate_StringLength(0, 255), 'presence' => 'required', 'allowEmpty' => true),
		);
		
		try {
			
			
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
						
			$locale = Emerald_Model::get('DbTable_Locale')->find($input->locale)->current();
			if(!$locale) {
				throw new Exception('Locale not found');
			}
			
			$locale->setOption('title', $input->title);
						
			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'l:common/save_ok');
			
			
		} catch(Exception $e) {
			
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'l:common/save_failed');
			$msg->errorFields = array_keys($input->getMessages());
		}
		
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($msg);
		
		
		
		
	}
	
}