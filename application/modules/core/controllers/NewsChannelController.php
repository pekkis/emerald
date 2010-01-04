<?php
class Core_NewsChannelController extends Emerald_Controller_Action
{	
	public $ajaxable = array(
		'save' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
	public function editAction()
	{
		$filters = array();
		
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
		);
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$channelModel = new Core_Model_NewsChannel();
			$channel = $channelModel->find($input->id);
						
			$page = $this->_pageFromPageId($channel->page_id);
			
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
				throw new Emerald_Exception('Forbidden', 401);
			}

			$form = new Core_Form_NewsChannel();
			$form->setDefaults($channel->toArray());			
		
			
			$this->view->form = $form;
			
			
		} catch(Exception $e) {
			throw $e;
		}
		
		
		
	}
	
	
	public function saveAction()
	{
		
		$form = new Core_Form_NewsChannel();
		
		if($form->isValid($this->getRequest()->getPost())) {

			$page = $this->_pageFromPageId($form->page_id->getValue());
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
				throw new Emerald_Exception('Forbidden', 401);
			}
						
			$model = new Core_Model_NewsChannel();
			
			$item = $model->find($form->id->getValue());		
			
			$item->setFromArray($form->getValues());
			
			$model->save($item);

			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');
			
		} else {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
			$msg->errors = $form->getMessages(); 
		}
		
		$this->view->message = $msg;
		
	}
	
	
	public function addItemAction()
	{
		$filters = array();
		
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
		);
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$channelModel = new Core_Model_NewsChannel();
			$channel = $channelModel->find($input->id);
						
			$page = $this->_pageFromPageId($channel->page_id);
			
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
				throw new Emerald_Exception('Forbidden', 401);
			}

			$form = new Core_Form_NewsItem();
			$this->view->form = $form;
			
			$form->news_channel_id->setValue($input->id);
			
			$now = new DateTime();
			
			$form->valid_start->setValue($now->format('Y-m-d'));
			
			$now->modify("+ {$channel->default_months_valid} months");
			
			$form->valid_end->setValue($now->format('Y-m-d'));
			
			$form->status->setValue(1);
			
		} catch(Exception $e) {
			throw $e;
		}
	}
	
	
	

	
}