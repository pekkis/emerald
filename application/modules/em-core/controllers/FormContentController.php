<?php
class EmCore_FormContentController extends Emerald_Controller_Action 
{
	public $ajaxable = array(
		'save' => array('json'),
		'post' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
	public function indexAction()
	{
		$filters = array(
		);
		$validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
		);
						
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
				
			$page = $this->_pageFromPageId($input->page_id);
			$this->view->page = $page;
					
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read')) {
				throw new Emerald_Exception('Forbidden', 401);
			}
			
			$writable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write');
			$this->view->writable = $writable;
							
			$formContentModel = new EmCore_Model_FormContent();
			$formcontent = $formContentModel->findByPageId($page->id);
		
			$this->view->formcontent = $formcontent;
		
			if($formcontent && $formcontent->form_id) {
				
								
				$formModel = new EmCore_Model_Form();
				$form = $formModel->find($formcontent->form_id);
								
				$this->view->form = $form;
				
				
			}
		

		
				
		} catch(Exception $e) {
			throw $e;
		}
		
	}
	
	
	public function editAction()
	{
		$filters = array(
		);
		$validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
				
			$page = $this->_pageFromPageId($input->page_id);
			$this->view->page = $page;
					
			
			
			
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
				throw new Emerald_Exception('Forbidden', 401);
			}

			
			
			$formContentModel = new EmCore_Model_FormContent();
			$formcontent = $formContentModel->findByPageId($page->id);
		
			
			
			
			$this->view->formcontent = $formcontent;

			$form = new EmCore_Form_FormContent();
			
			
			
			$form->setDefaults($formcontent->toArray());
			
			$form->setLocale($page->locale);
			
			$this->view->form = $form;
			
			
			
		} catch(Exception $e) {
			throw $e;
		}
		
		
	}
	
	
	
	public function saveAction()
	{
		$form = new EmCore_Form_FormContent();

		$page = $this->_pageFromPageId($this->_getParam('page_id'));
		if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
			throw new Emerald_Exception('Forbidden', 401);
		}
		
		$this->view->page = $page;

		$form->setLocale($page->locale);
		
		if($form->isValid($this->getRequest()->getPost())) {

			$form->setLocale($page->locale);
			
			$model = new EmCore_Model_FormContent();
			$item = $model->findByPageId($form->page_id->getValue());		
			
			$item->setFromArray($form->getValues());
			$model->save($item);

			$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok');
			
		} else {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Save failed');
			$msg->errors = $form->getMessages(); 
		}
		
		$this->view->message = $msg;
	}
	
	
	
	public function postAction()
	{
		
		$filters = array();
		$validators = array(
			'page_id' => array('Int', 'presence' => 'required'),
		);

		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->process();
			
			$page = $this->_pageFromPageId($this->_getParam('page_id'));
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read')) {
				throw new Emerald_Exception('Forbidden', 401);
			}
			
			$formContentModel = new EmCore_Model_FormContent();
			$formcontent = $formContentModel->findByPageId($page->id);

			$formModel = new EmCore_Model_Form();
			$form = $formModel->find($formcontent->form_id);
			
			// Zend_Debug::dump($form);
			
			$zform = $form->getZendForm();
			
			if($zform->isValid($this->getRequest()->getPost())) {
				


				$mail = new Zend_Mail('UTF8');
					
				$mail->setSubject($formcontent->email_subject);
				$mail->setFrom($formcontent->email_from);
				$mail->addTo($formcontent->email_to);
					
				$rows = array();
				foreach($zform->getValues() as $key => $fieldVal) {
					if(is_array($fieldVal)) {
						$fieldVal = implode(', ', $fieldVal);
					}
					$rows[] = $key . ': ' . $fieldVal;					
				}
					
				$mesg = implode("\n", $rows);

				$mail->setBodyText($mesg);
									
				$mail->send($transport);
				
				$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Great success!');
								
				$pageModel = new EmCore_Model_Page();
				$page = $pageModel->find($formcontent->redirect_page_id);

				$this->getHelper('redirector')->gotoUrlAndExit('/' . $page->beautifurl);
				
				
				
			} else {
				
				$msg = new Emerald_Message(Emerald_Message::ERROR, 'Epic fail!');
				$msg->errors = $zform->getMessages();
				
				$this->view->page = $page;
				$this->view->form = $form;
				$this->view->zform = $zform;
				$this->getHelper('viewRenderer')->setScriptAction('index');
				
			}
					
				
		} catch(Exception $e) {
					
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Mail send failure.');
			$msg->exception = $e->getMessage();
			
			
		}	
		
		$this->view->message = $msg;
		
		
	}
	
	
}