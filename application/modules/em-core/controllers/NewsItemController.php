<?php
class EmCore_NewsItemController extends Emerald_Controller_Action
{	
	public $ajaxable = array(
		'save' => array('json'),
		'delete' => array('json'),
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

			$newsItemModel = new EmCore_Model_NewsItem();
			$item = $newsItemModel->find($input->id);
						
			$channelModel = new EmCore_Model_NewsChannel();
			$channel = $channelModel->find($item->news_channel_id);
						
			$page = $this->_pageFromPageId($channel->page_id);
			
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
				throw new Emerald_Exception('Forbidden', 403);
			}

			$form = new EmCore_Form_NewsItem();
			$form->setDefaults($item->toArray());			
					
			$this->view->form = $form;
			$this->view->page = $page;
			
		} catch(Exception $e) {
			throw $e;
		}
		
		
		
	}
	
	
	public function deleteAction()
	{
		$filters = array();
		
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
		);
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			$newsItemModel = new EmCore_Model_NewsItem();
			$item = $newsItemModel->find($input->id);
						
			$channelModel = new EmCore_Model_NewsChannel();
			$channel = $channelModel->find($item->news_channel_id);
						
			$page = $this->_pageFromPageId($channel->page_id);
			
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
				throw new Emerald_Exception('Forbidden', 403);
			}

			$newsItemModel->delete($item);
						
			$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Delete ok');
			
		} catch(Exception $e) {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Delete failed');
		}
		
		$this->view->message = $msg;
		
		
	}
	
	
	
	
	public function saveAction()
	{
		$form = new EmCore_Form_NewsItem();
		if($form->isValid($this->getRequest()->getPost())) {
						
			$channelModel = new EmCore_Model_NewsChannel();
			$channel = $channelModel->find($form->news_channel_id->getValue());
			
			$page = $this->_pageFromPageId($channel->page_id);			
			
			if(!$this->getAcl()->isAllowed($this->getCurrentUser(), $page, 'write')) {
				throw new Emerald_Exception('Forbidden', 403);
			}
						
			$model = new EmCore_Model_NewsItem();
			
					
			if(!$form->id->getValue() || !$item = $model->find($form->id->getValue())) {
				$item = new EmCore_Model_NewsItemItem();
			}
			
			$item->setFromArray($form->getValues());
			
			$model->save($item);

			$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok');
			$msg->saved_item_id = $item->id;
			
		} else {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Save failed');
			$msg->errors = $form->getMessages(); 
		}
		
		$this->view->message = $msg;
		
	}
	
	

	
}