<?php
class Admin_FolderController extends Emerald_Controller_Action
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
			
		$fl = Zend_Registry::get('Emerald_Filelib');
		$folder = $fl->findFolder($this->_getParam('id'));
		
		try {
			$fl->deleteFolder($folder);
			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Great success');
		} catch(Exception $e) {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Epic fail');
		}
		
		$this->view->message = $msg;
		
	}
	
	
	public function editAction()
	{
		
		$fl = Zend_Registry::get('Emerald_Filelib');
		$folder = $fl->findFolder($this->_getParam('id'));
				
		$form = new Admin_Form_Folder(); 
		$form->setDefaults($folder->toArray());
		
		$folderModel = new Core_Model_Folder();
				
		$permForm = $form->getSubForm('folder-permissions');
		$permissions = $folderModel->getPermissions($folder);
		
		$permForm->setDefaults($permissions);
				
		$this->view->form = $form;
		
		
	}
	
	
	
	
	public function saveAction()
	{
		$form = new Admin_Form_Folder();
				
		if($form->isValid($this->_getAllParams())) {

			$fl = Zend_Registry::get('Emerald_Filelib');
			$folder = $fl->findFolder($form->id->getValue());

			$fl->updateFolder($folder);

			$folderModel = new Core_Model_Folder();
			$folderModel->savePermissions($folder, $form->getSubForm('folder-permissions')->getValues());
						
			$this->getAcl()->remove($folder);
			
			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');
			$this->view->message->folder_id = $folder->id;
			
		} else {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
			$msg->errors = $form->getMessages();
			$this->view->message = $msg;
		}
		
		
		
	}
	

}