<?php
class EmAdmin_FolderController extends Emerald_Controller_Action
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
		$groupModel = new EmCore_Model_Group();
		$this->view->groups = $groupModel->findAll();
		
		$groupModel = new EmCore_Model_Group();
		$this->view->groups = $groupModel->findAll();
		
		
	}
	
	
	public function createAction()
	{
		$groupModel = new EmCore_Model_Group();
		$form = new EmAdmin_Form_Group();
				
		$this->view->form = $form;
		
	}
	
	
	public function deleteAction()
	{
			
		$fl = Zend_Registry::get('Emerald_Filelib');
		$folder = $fl->folder()->find($this->_getParam('id'));
		
		try {
			$fl->deleteFolder($folder);
			$msg = new Emerald_Message(Emerald_Message::SUCCESS, 'Great success');
		} catch(Exception $e) {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Epic fail');
		}
		
		$this->view->message = $msg;
		
	}
	
	
	public function editAction()
	{
		
		$fl = Zend_Registry::get('Emerald_Filelib');
		$folder = $fl->folder()->find($this->_getParam('id'));
				
		$form = new EmAdmin_Form_Folder(); 
		$form->setDefaults($folder->toArray());
		
		$folderModel = new EmCore_Model_Folder();
				
		$permForm = $form->getSubForm('folder-permissions');
		$permissions = $folderModel->getPermissions($folder);
		
		$permForm->setDefaults($permissions);
				
		$this->view->form = $form;
		
		
	}
	
	
	
	
	public function saveAction()
	{
		$form = new EmAdmin_Form_Folder();
				
		if($form->isValid($this->_getAllParams())) {

			$fl = Zend_Registry::get('Emerald_Filelib');
			$folder = $fl->folder()->find($form->id->getValue());

			
			$folder->name = $form->name->getValue();
						
			// $folder->setFromArray($form->getValues());
			
			$fl->folder()->update($folder);

			$folderModel = new EmCore_Model_Folder();
			$folderModel->savePermissions($folder, $form->getSubForm('folder-permissions')->getValues());
			
			$fl->folder()->update($folder);
			
			// $this->getAcl()->cacheRemove();
			
			$this->view->message = new Emerald_Message(Emerald_Message::SUCCESS, 'Save ok!');
			$this->view->message->folder_id = $folder->id;
			
		} else {
			$msg = new Emerald_Message(Emerald_Message::ERROR, 'Save failed');
			$msg->errors = $form->getMessages();
			$this->view->message = $msg;
		}
		
		
		
	}
	

}