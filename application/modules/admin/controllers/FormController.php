<?php
class Admin_FormController extends Emerald_Controller_AdminAction 
{
	public $ajaxable = array(
		'save' => array('json'),
		'delete' => array('json'),
		'create' => array('json'),
		'field-create' => array('json'),
		'field-delete' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
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
	
	
	public function deleteAction()
	{
		if(!$this->getCurrentUser()->inGroup(Core_Model_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		$model = new Core_Model_Form();
		$item = $model->find($this->_getParam('id'));
		
		try {
			$model->delete($item);
			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Delete ok');	
		} catch(Emerald_Exception $e) {
			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Delete failed');
		}
		
	}
	
	
	public function createAction()
	{
		if(!$this->getCurrentUser()->inGroup(Core_Model_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}

			
		$form = new Admin_Form_FormCreate();
		$model = new Core_Model_Form();
		
		if($form->isValid($this->_getAllParams())) {
			$item = new Core_Model_FormItem();
			$item->setFromArray($form->getValues());
			$model->save($item);
			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Operation ok');
		} else {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Operation failed');
			$msg->errors = $form->getMessages();
			$this->view->message = $msg;
		}
		
	}
	
	
	public function fieldDeleteAction()
	{
		if(!$this->getCurrentUser()->inGroup(Core_Model_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}

		
		$model = new Core_Model_FormField();
		

		try {
			$item = $model->find($this->_getParam('id'));
			$model->delete($item);
			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Great success.');
		} catch(Exception $e) {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Epic fail.');
		}
		$this->view->message = $msg;
	}
	
	
	
	
	public function fieldCreateAction()
	{
		if(!$this->getCurrentUser()->inGroup(Core_Model_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 403);
		}
		
		
		$form = new Admin_Form_FormFieldCreate();

		if($form->isValid($this->_getAllParams())) {
			
			$model = new Core_Model_Form();
			
			$formObj = $model->find($form->form_id->getValue());
						
			$field = new Core_Model_FormFieldItem($form->getValues());
									
			$field->order_id = $model->getOrderIdForNewField($formObj);
			$model->saveField($field);
			
			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Great success.');
		} else {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Epic fail.');
		}

		$this->view->message = $msg;
				
	}
	
	
	
	
	public function saveAction()
	{
		if(!$this->getCurrentUser()->inGroup(Core_Model_Group::GROUP_ROOT))
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

			
			$fieldModel = new Core_Model_FormField(); 

			$order = 0;
			
			foreach($input->id as $key => $id) {
				$field = $fieldModel->find($id);
				$field->order_id = $order++;
				$field->type = $input->type[$key];
				$field->title = $input->title[$key];
				$field->mandatory = $input->mandatory[$key];
				$field->options = $input->options[$key];
				$fieldModel->save($field);
			}
			
			
			$db->commit();
			
			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok.');
									
		
		} catch(Exception $e) {

			echo $e;
			
			$db->rollBack();
						
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed.');
			$msg->errors = array_keys($input->getMessages());
			
		}
		
		$this->view->message = $msg;
		
		
	}
	
	
	
	public function editAction()
	{
		if(!$this->getCurrentUser()->inGroup(Core_Model_Group::GROUP_ROOT))
		{
		 	throw new Emerald_Exception("Forbidden", 401);
		}
				
		
		$filters = array();
		$validators = array(
			'id' => array('Int', 'presence' => 'required'),
		);
		
		try {

			$input = new Zend_Filter_Input($filters, $validators, $this->_getAllParams());
			$input->setDefaultEscapeFilter(new Emerald_Filter_HtmlSpecialChars());
			$input->process();
		
			$formModel = new Core_Model_Form();
						
			if(!$form = $formModel->find($input->id)) {
				throw new Emerald_Exception('Invalid form', 404);
			}
					
			
			$this->view->form = $form;

			$createForm = new Admin_Form_FormFieldCreate();
			$createForm->form_id->setValue($form->id);
			
			$this->view->fieldCreateForm = $createForm; 
						
			
			$opts = array(
			'1' => '{l:admin/form/field/type/1}',
			'2' => '{l:admin/form/field/type/2}',
			'3' => '{l:admin/form/field/type/3}',
			'4' => '{l:admin/form/field/type/4}',
			'5' => '{l:admin/form/field/type/5}',
			'6' => '{l:admin/form/field/type/6}',
			);
			
			$this->view->opts = $opts;
			

									
			
		} catch(Exception $e) {
			throw $e;
		}
			
		
	}
	
	
	
	
	
}