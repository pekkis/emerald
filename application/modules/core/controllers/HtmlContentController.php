<?php
class Core_HtmlcontentController extends Emerald_Controller_Action 
{

	public $ajaxable = array(
		'save' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	

	
	public function indexAction()
	{
		$filters = array();
		$validators = array(
			'page_id' => array(new Zend_Validate_Int(), 'presence' => 'required', 'allowEmpty' => false),
			'block_id' => array('Int', 'presence' => 'required'),
			'onEmpty' => array(new Zend_Validate_StringLength(0, 255), 'allowEmpty' => true, 'presence' => 'optional', 'default' => ''),	
		);
						
		try {
			$input = new Zend_Filter_Input($filters, $validators, $this->getRequest()->getUserParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$page = $this->_pageFromPageId($input->page_id);
			
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'read')) {
				throw new Emerald_Acl_ForbiddenException('Forbidden');
			}
			
			$writable = $this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write');
			$this->view->writable = $writable;

			$htmlModel = new Core_Model_HtmlContent();
									
			$htmlcontent = $htmlModel->find($page->id, $input->block_id);			
			
			if(!$htmlcontent->content && $input->onEmpty) {
				$htmlcontent->content = $input->onEmpty;
			}
						
			$this->view->htmlcontent = $htmlcontent;
			
		} catch(Exception $e) {
			
			throw $e;
			
			
		}
		
	}
	
	
	
	public function editAction()
	{
				
		$filters = array(
		);
		
		$validators = array(
			'page_id' => array('Int', 'presence' => 'required'),
			'block_id' => array('Int', 'presence' => 'required'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$htmlModel = new Core_Model_HtmlContent();
			$htmlcontent = $htmlModel->find($input->page_id, $input->block_id);			
			
			$this->view->htmlcontent = $htmlcontent;
			
			$this->view->layout()->setLayout("admin-popup");

			$form = new Core_Form_HtmlContent();
			$form->setDefaults($htmlcontent->toArray());
			
			$this->view->form = $form;
						
			
		} catch(Exception $e) {
			throw new Emerald_Exception($e->getMessage(), 500);
		}
		
	}
	
	
	
	public function saveAction()
	{
		
		$form = new Core_Form_HtmlContent();
		
		if($form->isValid($this->getRequest()->getPost())) {
			
			$page = $this->_pageFromPageId($form->page_id->getValue());
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
				throw new Emerald_Acl_ForbiddenException('Forbidden');
			}

			
			$model = new Core_Model_HtmlContent();
			
			$htmlcontent = $model->find($form->page_id->getValue(), $form->block_id->getValue());		
			
			$htmlcontent->setFromArray($form->getValues());
			
			$model->save($htmlcontent);

			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');
			
			
		} else {
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
			$message->errors = array_keys($input->getMessages()); 
		}
		
		$this->view->message = $msg;
		
	}
	
	
	
	
	
}