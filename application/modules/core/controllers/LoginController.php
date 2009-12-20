<?php
class Core_LoginController extends Emerald_Controller_Action
{

	public function init()
	{
		/*
		$ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('handle', 'json')->initContext();
       	*/          
	}
	
	
	public function pageAction()
	{
		$filters = array(
		);
		$validators = array(
			'page' => array(new Emerald_Validate_InstanceOf('Emerald_Page'), 'presence' => 'required'),
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$writable = Zend_Registry::get('Emerald_Acl')->isAllowed($this->getCurrentUser(), $input->page, 'write');
			$this->view->writable = $writable;
			
			$this->view->user = $this->getCurrentUser();
			$this->view->page = $input->page;

			$loginTbl = Emerald_Model::get('LoginRedirect');
			if($res = $loginTbl->find($input->page->id)->current()) {
				$page = Emerald_Page::find($res->redirect_page_id);
				$redirectUrl = $page->beautifurl; 				
			} else {
				$redirectUrl = $this->view->page->beautifurl;
			}

			$this->view->redirectUrl = $redirectUrl;
			
			
		} catch(Exception $e) {
			throw $e;
		}
		
			
		
		
	}
	
	
	
	
	public function indexAction()
	{
		$this->view->form = new Core_Form_Login();
	}
	public function handleAction()
	{
		$this->_helper->getHelper('AjaxContext')->initContext('json');		
		
		
		$filters = array(
		);
		
		$validators = array(
			'email' => array(new Zend_Validate_EmailAddress(), 'presence' => 'required'),
			'passwd' => array('Alnum', 'presence' => 'required')
		);

		
						
		
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();
						
			$auth = Zend_Auth::getInstance();
			$adapter = new Zend_Auth_Adapter_DbTable($this->getDb(), 'user', 'email', 'passwd', 'MD5(?) and status = 1');			
						
			$adapter->setIdentity($input->email);
			$adapter->setCredential($input->passwd);

			$result = $auth->authenticate($adapter);
			
			if($result->isValid()) {
				
				$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, Zend_Registry::get('Zend_Translate')->translate('Login was successful.'));
				
				
				$auth->getStorage()->write($adapter->getResultRowObject()->id);

								Zend_Debug::dump($adapter->getResultRowObject());
				die();
				
				
				
			} else {
				$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, Zend_Registry::get('Zend_Translate')->translate('User was not authenticated.'));
			}
			
		} catch(Exception $e) {
			if($input->hasMissing()) {
				$msg = Zend_Registry::get('Zend_Translate')->translate('Please fill all required fields.');
			} elseif($input->hasInvalid()) {
				$msg = Zend_Registry::get('Zend_Translate')->translate('Please check all required fields.');
			} else {
				$msg = 'login/error/unknown';
			}
			
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, $msg);
			
		}
		
			
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->getResponse()->appendBody($msg);
		
		
		
	}
	
	
	
}
