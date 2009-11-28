<?php
class EmeraldAdmin_NewsitemController extends Emerald_Controller_AdminAction
{
	
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
									
			$itemTbl = Emerald_Model::get('NewsItem');
			$item = $itemTbl->find($input->id)->current();

			$page = $item->getChannel()->getPage();
			$page->assertWritable();
			
			$item->delete();
						
			$this->getResponse()->setRedirect("/{$page->iisiurl}");
			
			
			
		} catch(Exception $e) {
			throw new Emerald_Exception('Fatal Error', 500);
		}
		
		
		
		
	}
	
	
	
	public function addAction()
	{
		$filters = array();
		
		$validators = array(
			'channel_id' => array('Int', 'presence' => 'required'),
		);
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$channelTbl = Emerald_Model::get('NewsChannel');
			$channel = $channelTbl->find($input->channel_id)->current();
			
			$page = $channel->getPage();
			$page->assertWritable();
		
			$this->view->channel = $channel;
			$this->view->layout()->setLayout("admin_popup_outer");
			$this->view->headScript()->appendFile('/lib/js/admin/newsitem/add.js');
						
			$itemTbl = Emerald_Model::get('NewsItem');

			$item = $itemTbl->createRow(array(), true);
			$this->view->item = $item;
			
		} catch(Exception $e) {
			throw new Emerald_Exception('Internal Server Error', 500);
		}
		
		
		
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
									
			$itemTbl = Emerald_Model::get('NewsItem');
			$item = $itemTbl->find($input->id)->current();

			$page = $item->getChannel()->getPage();
			$page->assertWritable();
			
			$this->view->item = $item;
			$this->view->layout()->setLayout("admin_popup_outer");
			$this->view->headScript()->appendFile('/lib/js/admin/newsitem/add.js');
			
		} catch(Exception $e) {
			throw new Emerald_Exception('Internal Server Error', 500);
		}
		
	}
	
	
	
	
	public function insertAction()
	{
		
		
		$filter = new Emerald_Filter_SelectDate();
		$this->_setParam('valid_start', $filter->filter($this->_getParam('valid_start')));
		$this->_setParam('valid_end', $filter->filter($this->_getParam('valid_end')));
		
		$filters = array(
		);
		
		$validators = array(
			'channel_id' => array('Int', 'presence' => 'required'),
			'title' => array(new Zend_Validate_StringLength(1, 255), 'presence' => 'required'),
			'description' => array(new Zend_Validate_StringLength(1), 'presence' => 'required'),
			'article' => array(new Zend_Validate_StringLength(1), 'presence' => 'required'),
			'status' => array('Int', 'presence' => 'required'),
			'valid_start' => array(new Emerald_Validate_Datetime()),
			'valid_end' => array(new Emerald_Validate_Datetime()),
		);
						
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
						
			
			$channelTbl = Emerald_Model::get('NewsChannel');
			$channel = $channelTbl->find($input->channel_id)->current();
			
			$pageTbl = Emerald_Model::get('Page');
			$page = $pageTbl->find($channel->page_id)->current();
			$page->assertWritable();
			
			 			
			$itemTbl = Emerald_Model::get('NewsItem');
			$item = $itemTbl->createRow(array(), true);
			
			$item->news_channel_id = $channel->id;
			$item->title = $input->getUnescaped('title');
			$item->description = $input->getUnescaped('description');
			$item->article = $input->getUnescaped('article');
			$item->status = $input->status;
			$item->valid_start = $input->valid_start;
			$item->valid_end = $input->valid_end;			
			
			$item->save();
			
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
	
	public function updateAction()
	{
		$filter = new Emerald_Filter_SelectDate();
		$this->_setParam('valid_start', $filter->filter($this->_getParam('valid_start')));
		$this->_setParam('valid_end', $filter->filter($this->_getParam('valid_end')));
		
		$filters = array(
		);
		
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
			'title' => array(new Zend_Validate_StringLength(1, 255), 'presence' => 'required'),
			'description' => array(new Zend_Validate_StringLength(1), 'presence' => 'required'),
			'article' => array(new Zend_Validate_StringLength(1), 'presence' => 'required'),
			'status' => array('Int', 'presence' => 'required'),
			'valid_start' => array(new Emerald_Validate_Datetime()),
			'valid_end' => array(new Emerald_Validate_Datetime()),
		);
						
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$itemTbl = Emerald_Model::get('NewsItem');
			$item = $itemTbl->find($input->id)->current();
						
			$channelTbl = Emerald_Model::get('NewsChannel');
			$channel = $channelTbl->find($item->news_channel_id)->current();
			
			$pageTbl = Emerald_Model::get('Page');
			$page = $pageTbl->find($channel->page_id)->current();
			$page->assertWritable();
			
			$item->title = $input->getUnescaped('title');
			$item->description = $input->getUnescaped('description');
			$item->article = $input->getUnescaped('article');
			$item->status = $input->status;
			$item->valid_start = $input->valid_start;
			$item->valid_end = $input->valid_end;			
			
			$item->save();
			
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Update ok');
			
			
		} catch(Exception $e) {
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Update failed');
			$message->errorFields = array_keys($input->getMessages()); 
			$message->exception = $e->getMessage();
		}
				
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
		
		
	}
	
}