<?php
class EmeraldAdmin_NewschannelController extends Emerald_Controller_AdminAction
{
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

			$id = $input->id;
			$channelTbl = Emerald_Model::get('NewsChannel');
			$channel = $channelTbl->find($id)->current();
			
			$page = $channel->getPage();
			$page->assertWritable();
					
			$this->view->channel = $channel;
			$this->view->layout()->setLayout("admin_popup_outer");
			$this->view->headScript()->appendFile('/lib/js/emerald-admin/newschannel/edit.js');
			
		} catch(Exception $e) {
			throw new Emerald_Exception('Internal Server Error', 500);
		}
		
		
		
	}
	
	
	public function saveAction()
	{
		
		$filters = array();
		
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
			'title' => array(new Zend_Validate_StringLength(1, 255), 'presence' => 'required'),
			'description' => array(new Zend_Validate_StringLength(0), 'presence' => 'required'),
			'link_readmore' => array(new Zend_Validate_StringLength(1, 255), 'presence' => 'required'),
			'items_per_page' => array('Int', 'presence' => 'required'),
			'default_months_valid' => array('Digits', 'presence' => 'required'),
			'allow_syndication' => array('Int', 'presence' => 'required'),
		);
				
		
		try {
			
			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			
			$input->process();
			
			$channelTbl = Emerald_Model::get('NewsChannel');
			$channel = $channelTbl->find($input->id)->current();

			$page = $channel->getPage();
			$page->assertWritable();
			
			
			
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');
			
			$channel->title = $input->title;
			$channel->link_readmore = $input->link_readmore;
			$channel->description = $input->getUnescaped('description');
			$channel->default_months_valid = $input->default_months_valid;
			$channel->items_per_page = $input->items_per_page;
			$channel->allow_syndication = $input->allow_syndication;
			$channel->save();
			
		} catch(Exception $e) {
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
			$message->errorFields = array_keys($input->getMessages()); 
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
		
		
	}
	
	
}