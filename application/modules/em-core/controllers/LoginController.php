<?php
class EmCore_LoginController extends Emerald_Controller_Action
{

	
	
	
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
		$this->view->form = new EmCore_Form_Login();
	}

		
	

	
	
	
}
