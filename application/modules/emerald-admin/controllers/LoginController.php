<?php
class EmeraldAdmin_LoginController extends Emerald_Controller_AdminAction 
{
	
	
	
	public function saveAction()
	{
		$filters = array();
		$validators = array(
			'page_id' => array('Int', 'presence' => 'required'),
			'redirect_page_id' => array('Int', 'presence' => 'required'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$loginTbl = Emerald_Model::get('LoginRedirect');
			
			if(!$res = $loginTbl->find($input->page_id)->current()) {
				$res = $loginTbl->createRow();
			}
			
			
			$res->page_id = $input->page_id;
			$res->redirect_page_id = $input->redirect_page_id;
			$res->save();
			
					
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Insert ok');
			
			
		} catch(Exception $e) {

			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Insert failed');
			$message->errorFields = array_keys($input->getMessages()); 
			$message->exception = $e->getMessage();
			
			
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);		
	}
	
	
	
	public function editAction()
	{
		$filters = array();
		$validators = array(
			'page_id' => array('Int', 'presence' => 'required'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
		
			$loginTbl = Emerald_Model::get('LoginRedirect');
			
			if(!$res = $loginTbl->find($input->page_id)->current()) {
				$res = $loginTbl->createRow();
			}

			// Emerald_Js::addjQueryUi($this->view);
			// $this->view->headScript()->appendFile('/lib/js/admin/form/edit.js');
			
			$this->view->res = $res;
			$this->view->page_id = $input->page_id;

			$this->view->sitemap = new Emerald_Sitemap('fi', $this->getCurrentUser()->id);
			
			
		} catch(Exception $e) {
			throw new Emerald_Exception($e->getMessage(), 500);
		}
			
		
	}
	
	
	
	
	
}