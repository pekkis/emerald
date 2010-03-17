<?php
class Admin_FormController extends Emerald_Controller_Action 
{
	public $ajaxable = array(
		'save' => array('json'),
		'delete' => array('json'),
		'create-post' => array('json'),
		'field-create' => array('json'),
		'field-delete' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
	
	public function indexAction()
	{
		$formModel = new Core_Model_Form();
		$forms = $formModel->findAll();
		$this->view->forms = $forms;
	}
	
	
	public function deleteAction()
	{
		
		$model = new Core_Model_Form();
		$item = $model->find($this->_getParam('id'));
		
		try {
			$model->delete($item);
			$this->view->message = new Emerald_Message(Emerald_Message::SUCCESS, 'Delete ok');	
		} catch(Emerald_Exception $e) {
			$this->view->message = new Emerald_Message(Emerald_Message::ERROR, 'Delete failed');
		}
		
	}
	
	
	public function createAction()
	{
		$this->view->form = new Admin_Form_FormCreate();
		
	}
	
	
	public function createPostAction()
	{
			
		$form = new Admin_Form_FormCreate();
		$model = new Core_Model_Form();
		
		if($form->isValid($this->_getAllParams())) {
			$item = new Core_Model_FormItem();
			$item->setFromArray($form->getValues());
			$model->save($item);
			
			$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Operation ok');
			$msg->form_id = $item->id;
		} else {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Operation failed');
			$msg->errors = $form->getMessages();
		}
		
		$this->view->message = $msg;
		
	}
	
	
	public function fieldDeleteAction()
	{
		$model = new Core_Model_FormField();

		try {
			$item = $model->find($this->_getParam('id'));
			$model->delete($item);
			$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Great success.');
		} catch(Exception $e) {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Epic fail.');
		}
		$this->view->message = $msg;
	}
	
	
	
	
	public function fieldCreateAction()
	{
		$form = new Admin_Form_FormFieldCreate();

		if($form->isValid($this->_getAllParams())) {
			
			$model = new Core_Model_Form();
			
			$formObj = $model->find($form->form_id->getValue());
						
			$field = new Core_Model_FormFieldItem($form->getValues());
									
			$field->order_id = $model->getOrderIdForNewField($formObj);
			$model->saveField($field);
			
			$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Great success.');
		} else {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Epic fail.');
		}

		$this->view->message = $msg;
				
	}
	
	
	
	
	public function saveAction()
	{
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
			
			$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok.');
									
		
		} catch(Exception $e) {

			echo $e;
			
			$db->rollBack();
						
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Save failed.');
			$msg->errors = array_keys($input->getMessages());
			
		}
		
		$this->view->message = $msg;
		
		
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
		
			$formModel = new Core_Model_Form();
						
			if(!$form = $formModel->find($input->id)) {
				throw new Emerald_Exception('Invalid form', 404);
			}
					
			
			$this->view->form = $form;

			$createForm = new Admin_Form_FormFieldCreate();
			$createForm->form_id->setValue($form->id);
			
			$this->view->fieldCreateForm = $createForm; 
						
			
			$opts = array(
			'1' => 'Text',
			'2' => 'Textarea',
			'3' => 'Select',
			'4' => 'Multiselect',
			'5' => 'Radio',
			'6' => 'Checkbox',
			);
			
			$this->view->opts = $opts;
			

									
			
		} catch(Exception $e) {
			throw $e;
		}
			
		
	}
	
	
	
	
	
}