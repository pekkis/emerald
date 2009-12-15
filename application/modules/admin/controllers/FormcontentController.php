<?php
class Admin_FormcontentController extends Emerald_Controller_Action
{
	public function editAction()
	{
		
		$filters = array(
		);
		
		$validators = array(
			'page_id' => array('Int', 'presence' => 'required'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$pageTbl = Emerald_Model::get('DbTable_Page');
			$page = $pageTbl->find($input->page_id)->current();
			$page->assertWritable();

			
			$formcontentTbl = Emerald_Model::get('Formcontent');
			
			if(!$formcontent = $formcontentTbl->find($input->page_id)->current()) {
								
				$formcontent = $formcontentTbl->createRow();
				
				$formcontent->page_id = $input->page_id;
				
				$formcontent->email_subject = $this->view->translate('Default subject');
				
				$formcontent->email_from = $formcontent->email_to = $this->getCurrentUser()->email;
			}
			
			$formIdOptions = array('' => $this->view->translate('Select form'));
			
			$formTbl = Emerald_Model::get('Form');
			
			$forms = $formTbl->fetchAll(null, 'name DESC');
			foreach($forms as $form) {
				$formIdOptions[$form->id] = $form->name;	
			}
							
			$this->view->item = $formcontent;
			
			$this->view->form_id_options = $formIdOptions;
			
			$this->view->layout()->setLayout("admin_popup_outer");
			$this->view->headScript()->appendFile('/lib/js/admin/formcontent/edit.js');
						
			
			
						
		} catch(Emerald_Acl_ForbiddenException $e) {
			throw $e;						
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
			'form_id' => array('Int', 'presence' => 'required'),
			'email_subject' => array(
				array('Alnum', true),
				array('StringLength', 1, 255)
			),
			'email_to' => array('EmailAddress', 'presence' => 'required'),
			'email_from' => array('EmailAddress', 'presence' => 'required'),
			'form_lock' => array('Int', 'presence' => 'required'),
			'redirect_page_id' => array('Int', 'presence' => 'required'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			$pageTbl = Emerald_Model::get('DbTable_Page');
			$page = $pageTbl->find($input->page_id)->current();
			$page->assertWritable();
			
			
			$now = new DateTime();
						
			$formcontentTbl = Emerald_Model::get('Formcontent');
			if(!$formcontent = $formcontentTbl->find($input->page_id)->current()) {
				$formcontent = $formcontentTbl->createRow();
				$formcontent->page_id = $input->page_id;
				$formcontent->created_by = $this->getCurrentUser()->id;
				$formcontent->created = $now->format('Y-m-d H:i:s');

			} else {
				$formcontent->modified_by = $this->getCurrentUser()->id;
				$formcontent->modified = $now->format('Y-m-d H:i:s');
			}
			
			
			
			$formcontent->form_id = $input->form_id;
			$formcontent->email_subject = $input->email_subject;
			$formcontent->email_from = $input->email_from;
			$formcontent->email_to = $input->email_to;
			$formcontent->form_lock = $input->form_lock;			
			$formcontent->redirect_page_id = $input->redirect_page_id;
			
			$formcontent->save();
			
			$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');
			
		} catch(Exception $e) {
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
			$message->errorFields = array_keys($input->getMessages()); 
		}
		
		$this->_helper->_layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
		
		
	}
	
	
}
?>