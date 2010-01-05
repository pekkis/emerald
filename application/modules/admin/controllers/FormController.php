<?php
class Admin_FormController extends Emerald_Controller_AdminAction 
{
	
	public function deleteAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$filters = array();
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
						
			$formTbl = Emerald_Model::get('Form');
			$where = $formTbl->getAdapter()->quoteInto('id = ?', $input->id);
			$formTbl->delete($where);
			
			$this->getResponse()->setRedirect("/admin/form");
			
		} catch(Exception $e) {
			$this->getResponse()->setRedirect("/admin/form/edit/id/{$input->form_id}");
		}
		
		
		
		
		
		
	}
	
	
	public function deletefieldAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$filters = array();
		$validators = array(
			'form_id' => array('Int', 'presence' => 'required'),
			'id' => array('Int', 'presence' => 'required'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
						
			$fieldTbl = Emerald_Model::get('Form_Field');

			$where = $fieldTbl->getAdapter()->quoteInto('id = ?', $input->id);
			$fieldTbl->delete($where);
			
			$this->getResponse()->setRedirect("/admin/form/edit/id/{$input->form_id}");
			
		} catch(Exception $e) {
			
			
			$this->getResponse()->setRedirect("/admin/form/edit/id/{$input->form_id}");
			
		}
		
		
			
	}
	
	
	
	
	public function createfieldAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$filters = array();
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
			'type' => array(new Zend_Validate_InArray(array(1, 2, 3, 4, 5, 6)), 'presence' => 'required'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
						
			$fieldTbl = Emerald_Model::get('Form_Field');
			
			$max = $fieldTbl->getAdapter()->fetchOne("SELECT MAX(order_id) FROM form_field WHERE form_id = ?", array($input->id));
						
			$field = $fieldTbl->createRow();
			$field->form_id = $input->id;
			$field->type = $input->type;
			$field->order_id = $max + 1;
			$field->mandatory = 0;
			
			$field->save();
			
			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Create ok.');
			
		} catch(Exception $e) {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Create failed.');
			$msg->errorFields = array_keys($input->getMessages());
		}
		
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($msg);
		
		
				
	}
	
	
	
	
	public function saveAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		
		$filters = array();
		$validators = array(
			'form_id' => array('Int', 'presence' => 'required'),
			'id' => array('Int', 'presence' => 'required'),
			'type' => array(new Zend_Validate_InArray(array(1, 2, 3, 4, 5, 6)), 'presence' => 'required'),
			'title' => array(new Zend_Validate_StringLength(1, 255), 'presence' => 'optional', 'allowEmpty' => true),
			'mandatory' => array(new Zend_Validate_InArray(array(0, 1)), 'presence' => 'required'),
			'options' => array(new Zend_Validate_StringLength(0, 255), 'presence' => 'optional', 'allowEmpty' => true),
		);

		
		$db = $this->getDb();
		$db->beginTransaction();
		
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
			
			
			//			Zend_Debug::dump($input->getEscaped());
						

			
			$fieldTbl = Emerald_Model::get('Form_Field'); 

			$order = 0;
			
			foreach($input->id as $key => $id) {
				
				$field = $fieldTbl->find($id)->current();
				$field->order_id = $order++;
				$field->type = $input->type[$key];
				$field->title = $input->title[$key];
				$field->mandatory = $input->mandatory[$key];
				$field->options = $input->options[$key];
				$field->save();
				
				unset($field);
				
			}
			
			
			$db->commit();
			
			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok.');
									
		
		} catch(Exception $e) {
			
			$db->rollBack();
						
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed.');
			$msg->errorFields = array_keys($input->getMessages());
			
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($msg);
		
		
		
	}
	
	
	
	public function editAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		
		$filters = array();
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
		
			$formTbl = Emerald_Model::get('Form');
			
			if(!$form = $formTbl->find($input->id)->current()) {
				throw new Emerald_Exception('Invalid form');
			}

			Emerald_Js::addjQueryUi($this->view);
			$this->view->headScript()->appendFile('/lib/js/admin/form/edit.js');
			
			$this->view->form = $form;

			
			$opts = array(
			'1' => '{l:admin/form/field/type/1}',
			'2' => '{l:admin/form/field/type/2}',
			'3' => '{l:admin/form/field/type/3}',
			'4' => '{l:admin/form/field/type/4}',
			'5' => '{l:admin/form/field/type/5}',
			'6' => '{l:admin/form/field/type/6}',
			);
			
			$this->view->opts = $opts;
			
			$this->view->headLink()->appendStylesheet('/lib/css/admin/form/form.css');
									
			
		} catch(Exception $e) {
			throw new Emerald_Exception($e->getMessage(), 500);
		}
			
		
	}
	
	
	
	public function indexAction()
	{
		if(!$this->getCurrentUser()->inGroup(Core_Model_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		
		$formModel = new Core_Model_Form();
		$forms = $formModel->findAll();
		
		$this->view->form = new Admin_Form_FormCreate();
		
		$this->view->forms = $forms;

		
	}
	
	
	public function createAction()
	{
		if(!$this->getCurrentUser()->inGroup(Emerald_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$filters = array();
		$validators = array(
			'name' => array(new Zend_Validate_StringLength(1, 255), 'presence' => 'required'),
			'description' => array(new Zend_Validate_StringLength(1, 1000), 'presence' => 'required'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();

			try {

				$now = new DateTime();
				
				$formTbl = Emerald_Model::get('Form');
				$formRow = $formTbl->createRow();

				$formRow->name = $input->name;
				$formRow->description = $input->description;
				
				$formRow->created = $now->format('Y-m-d H:i:s');
				$formRow->created_by = $this->getCurrentUser()->id;

				$formRow->status = 1;
				
				$formRow->save();
				
				$message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Create ok');
				
			} catch(Exception $e) {
				$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Create failed');
				$message->errorFields = array('name');
				$message->exception = $e->getMessage();
			}
			
			
			
		} catch(Zend_Filter_Exception $e) {
			$message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Create failed');
			$message->errorFields = array_keys($input->getMessages()); 
		}
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'text/javascript; charset=UTF-8');
		$this->getResponse()->appendBody($message);
			
		
		
	}
	
	
}