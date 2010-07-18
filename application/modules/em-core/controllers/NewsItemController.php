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
			
			$darr = $item->toArray();

			$split = explode(" ", $darr['valid_start']);
			
			$darr['valid_start_date'] = $split[0];
			$darr['valid_start_time'] = $split[1];

			$split = explode(" ", $darr['valid_end']);
			
			$darr['valid_end_date'] = $split[0];
			$darr['valid_end_time'] = $split[1];
			
			$form->setDefaults($darr);

			$tagForm = $form->getSubForm('tags');
			// No taggable, make it
			if(!$item->getTaggableId()) {
				$taggableModel = new EmCore_Model_Taggable();
				$taggableModel->registerFor($item);
				$newsItemModel->save($item);
			}
			$tagForm->setTaggable($item->getTaggable());
								
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
			
			$values = $form->getValues();
			$values['valid_start'] = $values['valid_start_date'] . ' ' . $values['valid_start_time']; 
			$values['valid_end'] = $values['valid_end_date'] . ' ' . $values['valid_end_time'];
						
			$item->setFromArray($values);

			// Tags
			$tags = $form->getSubForm('tags')->getValues();
			$taggableModel = new EmCore_Model_Taggable();
			
			$taggable = $taggableModel->registerFor($item);
			$taggable->setFromString($tags['tags']['tags']);
			$taggableModel->save($taggable);
			
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