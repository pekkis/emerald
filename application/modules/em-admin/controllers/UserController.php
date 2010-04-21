<?php
class EmAdmin_UserController extends Emerald_Controller_Action
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
		$userModel = new EmCore_Model_User();
		$this->view->users = $userModel->findAll();
		
		$groupModel = new EmCore_Model_Group();
		$this->view->groups = $groupModel->findAll();
		
		
	}
	
	
	public function createAction()
	{
		$userModel = new EmCore_Model_User();
								
		$form = new EmAdmin_Form_User(); 
				
		$this->view->form = $form;
		
	}
	
	
	public function deleteAction()
	{
		$userModel = new EmCore_Model_User();
		$user = $userModel->find($this->_getParam('id'));
		
		try {
			$userModel->delete($user);
			$this->view->message = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok');	
		} catch(Emerald_Exception $e) {
			$this->view->message = new Emerald_Message(Emerald_Message::ERROR, 'Save failed');
		}
		
		
		
		
	}
	
	
	public function editAction()
	{
		$userModel = new EmCore_Model_User();
		
		$user = $userModel->find($this->_getParam('id'));
				
		$form = new EmAdmin_Form_User(); 
		
		$form->setDefaults($user->toArray());

		$gopts = array();
		foreach($user->getGroups() as $group) {
			$gopts[] = $group->id;
		}
		$form->groups->setValue($gopts);
				
		$this->view->form = $form;
		
	}
	
	
	
	
	public function saveAction()
	{
		$form = new EmAdmin_Form_User();
		
		if(!$this->_getParam('id')) {
			$form->getSubForm('pwd')->password->setRequired(true);
			$form->getSubForm('pwd')->password->setAllowEmpty(false);
		}
		
		if($form->isValid($this->_getAllParams())) {
			
			$userModel = new EmCore_Model_User();
			if(!$form->id->getValue() || !$user = $userModel->find($form->id->getValue())) {
				$user = new EmCore_Model_UserItem();
			}

			$user->setFromArray($form->getValues());
			$userModel->save($user);
			
			$userModel->setGroups($user, $form->groups->getValue());
			
			$pwdForm = $form->getSubform('pwd');
			
			if($pwdForm->password->getValue()) {
				$userModel->setPassword($user, $pwdForm->password->getValue());
			}
			
			
			$this->view->message = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok');
			$this->view->message->user_id = $user->id;
			
			
		} else {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Save failed');
			$msg->errors = $form->getMessages();
			$this->view->message = $msg;
		}
		
		
		
	}
	

}