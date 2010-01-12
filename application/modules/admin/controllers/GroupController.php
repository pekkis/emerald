<?php
class Admin_GroupController extends Emerald_Controller_Action
{
	public $ajaxable = array(
		'save' => array('json'),
		'delete' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
	public function indexAction()
	{
		$groupModel = new Core_Model_Group();
		$this->view->groups = $groupModel->findAll();
		
		$groupModel = new Core_Model_Group();
		$this->view->groups = $groupModel->findAll();
		
		
	}
	
	
	public function createAction()
	{
		$groupModel = new Core_Model_Group();
								
		$form = new Admin_Form_Group(); 
				
		$this->view->form = $form;
		
	}
	
	
	public function deleteAction()
	{
		$groupModel = new Core_Model_Group();
		$group = $groupModel->find($this->_getParam('id'));
		
		try {
			$groupModel->delete($group);
			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');	
		} catch(Emerald_Exception $e) {
			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
		}
		
		
		
		
	}
	
	
	public function editAction()
	{
		$groupModel = new Core_Model_Group();
		
		$group = $groupModel->find($this->_getParam('id'));
				
		$form = new Admin_Form_Group(); 
		
		$form->setDefaults($group->toArray());
				
		$this->view->form = $form;
		
	}
	
	
	
	
	public function saveAction()
	{
		$form = new Admin_Form_Group();
		
		
		if($form->isValid($this->_getAllParams())) {
			
			$groupModel = new Core_Model_Group();
			if(!$form->id->getValue() || !$group = $groupModel->find($form->id->getValue())) {
				$group = new Core_Model_GroupItem();
				
			}

			$group->setFromArray($form->getValues());
						
			$groupModel->save($group);

			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');
			$this->view->message->group_id = $group->id;
			
		} else {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
			$msg->errors = $form->getMessages();
			$this->view->message = $msg;
		}
		
		
		
	}
	

}