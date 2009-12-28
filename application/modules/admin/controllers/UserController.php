<?php
class Admin_UserController extends Emerald_Controller_AdminAction
{
	public $ajaxable = array(
		'save' => array('json'),
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}
	
	public function indexAction()
	{
		$userModel = new Core_Model_User();
		$this->view->users = $userModel->findAll();
		
		$groupModel = new Core_Model_Group();
		$this->view->groups = $groupModel->findAll();
		
		
	}
	
	
	public function createAction()
	{
		$userModel = new Core_Model_User();
								
		$form = new Admin_Form_User(); 

		$form->getSubForm('pwd')->password->setRequired(true);
		$form->getSubForm('pwd')->password->setAllowEmpty(false);
		// $form->getSubForm('pwd')->password->setAttribute('Label', 'Password');		
		
				
		$this->view->form = $form;
		
	}
	
	
	public function editAction()
	{
		$userModel = new Core_Model_User();
		
		$user = $userModel->find($this->_getParam('id'));
				
		$form = new Admin_Form_User(); 
		
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
		
		$form = new Admin_Form_User();
		if($form->isValid($this->_getAllParams())) {
			
			$userModel = new Core_Model_User();
			if(!$user = $userModel->find($form->id->getValue())) {
				$user = new Core_Model_UserItem();
			}

			$user->setFromArray($form->getValues());
						
			$userModel->save($user);
			
			$userModel->setGroups($user, $form->groups->getValue());
			
			$pwdForm = $form->getSubform('pwd');
			
			if($pwdForm->password->getValue()) {
				$userModel->setPassword($user, $pwdForm->password->getValue());
			}
			
			
			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');
			
			
		} else {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
			$msg->errors = $form->getMessages();
			$this->view->message = $msg;
		}
		
		
		
	}
	

}