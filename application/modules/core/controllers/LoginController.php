<?php
class Core_LoginController extends Emerald_Controller_Action
{

	public $ajaxable = array(
        'handle'     => array('json'),
    );
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
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

		$form = new Core_Form_Login();
		if(!$form->isValid($this->getRequest()->getPost())) {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Check fields');
			$msg->errors = $form->getMessages(); 
		} else {
			
			$auth = Zend_Auth::getInstance();
			$adapter = new Zend_Auth_Adapter_DbTable($this->getDb(), 'user', 'email', 'passwd', 'MD5(?) and status = 1');			
						
			$adapter->setIdentity($form->tussi->getValue());
			$adapter->setCredential($form->loso->getValue());

			$result = $auth->authenticate($adapter);
			
			if($result->isValid()) {
				$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Login OK');
				// $auth->getStorage()->write($adapter->getResultRowObject());
			} else {
				$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Login failed.');
			}
			
			
		}

		$this->view->message = $msg;
		
		
		
	}
	
	
	
}
