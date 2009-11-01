<?php
class EmeraldAdmin_HtmlcontentController extends Emerald_Controller_Action
{
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
			
			$htmlcontentTbl = Emerald_Model::get('Htmlcontent');
			$htmlcontent = $htmlcontentTbl->find($input->page_id, $input->block_id)->current();
			
			if(!$htmlcontent) {
				$htmlcontent = $htmlcontentTbl->createRow();
				$htmlcontent->page_id = $input->page_id;
				$htmlcontent->block_id = $input->block_id;
				$htmlcontent->save();
			}
			
			$page = $htmlcontent->getPage();
			$page->assertWritable();									
			
			$this->view->htmlcontent = $htmlcontent;
			
			$this->view->layout()->setLayout("admin_popup_outer");
			
			$this->view->headScript()->appendFile('/lib/js/emerald-admin/htmlcontent/edit.js');
						
			
		} catch(Exception $e) {
			throw new Emerald_Exception($e->getMessage(), 500);
		}
		
	}
	
	
	
	public function saveAction()
	{
		
		$filters = array(
		);
		
		$validators = array(
			'page_id' => array('Int', 'presence' => 'required'),
			'block_id' => array('Int', 'presence' => 'required'),
			'content' => array(new Zend_Validate_StringLength(0), 'allowEmpty' => true, 'presence' => 'optional'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
						
			$htmlcontentTbl = Emerald_Model::get('Htmlcontent');
			$htmlcontent = $htmlcontentTbl->find($input->page_id, $input->block_id)->current();
			
			$page = $htmlcontent->getPage();
			$page->assertWritable();
						
			$htmlcontent->content = $input->getUnescaped('content');
			$htmlcontent->save();
						
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');
			
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
?>