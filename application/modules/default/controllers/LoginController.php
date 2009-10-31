<?php
class LoginController extends Emerald_Controller_Action
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
			
			$writable = $this->_emerald->getAcl()->isAllowed($this->_emerald->getUser(), $input->page, 'write');
			$this->view->writable = $writable;
			
			$this->view->user = $this->_emerald->getUser();
			$this->view->page = $input->page;

			$loginTbl = Emerald_Model::get('LoginRedirect');
			if($res = $loginTbl->find($input->page->id)->current()) {
				$page = Emerald_Page::find($res->redirect_page_id);
				$redirectUrl = $page->iisiurl; 				
			} else {
				$redirectUrl = $this->view->page->iisiurl;
			}

			$this->view->redirectUrl = $redirectUrl;
			
			
		} catch(Exception $e) {
			throw $e;
		}
		
			
		
		
	}
	
	
	
	
	public function indexAction()
	{
		$filters = array();
		
		$validators = array(
			'locale' => array(new Zend_Validate_Regex('/[a-z]{2,3}(_[A-Z]{2})?/'))
		);

		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();
			
			
						
			$this->view->layout()->disableLayout();
			$this->view->user = $this->_emerald->getUser();
			
			if(Zend_Locale::isLocale($input->locale) && in_array($input->locale, Emerald_Server::getInstance()->getAdminLocales()))
				$this->_emerald->setLocale($input->locale);
			else {
				
				$locale = new Zend_Locale('fi');
				$input->locale = $locale->getLanguage();
				$this->_emerald->setLocale($input->locale); 
			}
							

			$adminLocales = array();
			$locale = new Zend_Locale();
			foreach(Emerald_Server::getInstance()->getAdminLocales() as $pocale) {
				$adminLocales[$pocale] = $locale->getLanguageTranslation($pocale, $pocale);
			}

			$this->view->locale = $input->locale;
			$this->view->adminLocales = $adminLocales;
			
		} catch(Exception $e) {
			
			throw new Emerald_Exception($e->getMessage(), 500);
			
		}
	}
	public function handleAction()
	{
		if(!Zend_Session::sessionExists()) {
			$this->_emerald->initializeSession();
		}
				
		$this->_helper->getHelper('AjaxContext')->initContext('json');		
		
		
		$filters = array(
		);
		
		$validators = array(
			'email' => array(new Zend_Validate_EmailAddress(), 'presence' => 'required'),
			'passwd' => array('Alnum', 'presence' => 'required')
		);

		
		$oldFetchMode = $this->_db->getFetchMode();
				
		
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();
						
			$auth = Zend_Auth::getInstance();
			$adapter = new Zend_Auth_Adapter_DbTable($this->_db, 'user', 'email', 'passwd', 'MD5(?)');			

			$adapter->setIdentity($input->email);
			$adapter->setCredential($input->passwd);

			$this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);			
			$result = $auth->authenticate($adapter);
			$this->_db->setFetchMode($oldFetchMode);
			
			if($result->isValid()) {
				$row = $adapter->getResultRowObject('id');
				Zend_Session::regenerateId();
				$this->_emerald->getSession()->id = Zend_Session::getId();
				$this->_emerald->getSession()->user_id = $row->id;
				$this->_emerald->getSession()->save();
				$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, $this->_emerald->getTranslate()->_('login/message/ok'));
			} else {
				$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, $this->_emerald->getTranslate()->_('login/message/error_authentication_failed'));
			}
			
		} catch(Exception $e) {
			if($input->hasMissing()) {
				$msg = $this->_emerald->getTranslate()->_('login/message/error_empty_fields');
			} elseif($input->hasInvalid()) {
				$msg = $this->_emerald->getTranslate()->_('login/message/error_invalid_fields');
			} else {
				$msg = 'login/error/unknown';
			}
			
			$this->_db->setFetchMode($oldFetchMode);
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, $msg);
			
		}
		
			
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
        $this->getResponse()->appendBody($msg);
		
		
		
	}
	
	
	
}
