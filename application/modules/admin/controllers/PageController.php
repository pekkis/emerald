<?php
class Admin_PageController extends Emerald_Controller_AdminAction
{
	public $ajaxable = array(
		'save' => array('json'),
		'delete' => array('json'),
		'save-partial' => array('json')
	);
	
	public function init()
	{
		$this->getHelper('ajaxContext')->initContext();
	}

	public function deleteAction()
	{
		$pageModel = new Core_Model_Page();
		$page = $pageModel->find($this->_getParam('id'));
		
		try {
			$pageModel->delete($page);
			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Save ok');	
		} catch(Emerald_Exception $e) {
			$this->view->message = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Save failed');
		}
		
	}
	
		
	
	public function editAction()
	{
		$pageModel = new Core_Model_Page();

		$page = $pageModel->find($this->_getParam('id'));		
		
		$form = new Admin_Form_Page();
		$form->setLocale($page->locale);
		$form->setDefaults($page->toArray());

		$permForm = $form->getSubForm('page-permissions');
		$permissions = $pageModel->getPermissions($page);
		$permForm->setDefaults($permissions);
		
		/*
		foreach($permForm->getElements() as $key => $elm) {
			$elm->setDefaults($permissions[$key]);
		}
		*/
		
		$this->view->form = $form;
				
		
	}
	

	public function createAction()
	{
		// $pageModel = new Core_Model_Page();

		// $page = $pageModel->find($this->_getParam('id'));		
		
		$form = new Admin_Form_Page();
		$form->setLocale($this->_getParam('locale'));
		
		$form->parent_id->setValue($this->_getParam('id'));
		
		$permForm = $form->getSubForm('page-permissions');
		
		$permForm->setDefaults(array(Core_Model_Group::GROUP_ROOT => array_keys(Emerald_Permission::getAll())));
		
		
		// $form->setDefaults($page->toArray());
		
		$this->view->form = $form;
		
	}
	
	
	public function savePartialAction()
	{
		$pageModel = new Core_Model_Page();
		$form = new Admin_Form_Page();
		
		
		
		if($this->_getParam('id')) {
			$page = $pageModel->find($this->_getParam('id'));
			$form->setLocale($page->locale);
			
			$naviModel = new Core_Model_Navigation();
			$navi = $naviModel->getNavigation();
		
						
			if($form->isValidPartial($this->_getAllParams())) {
				foreach($form->getValues() as $key => $value) {
					if($value) {
						$page->$key = $value;
					}
				}
				// $page->setFromArray($form->getValues());
				$pageModel->save($page);

				$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Ok');
								
			} else {
				$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Failed');
				$msg->errors = $form->getMessages(); 
			} 
		
		} else {
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Failed');
		}		
		$this->view->message = $msg;
	}
	
	
	
	
	
	public function saveAction()
	{

		$pageModel = new Core_Model_Page();
		
		$form = new Admin_Form_Page();
		
		if($this->_getParam('id')) {
			$page = $pageModel->find($this->_getParam('id'));
			$form->setLocale($page->locale);
															
		} else {
			
			$parentId = $this->_getParam('parent_id');
			
			$page = new Core_Model_PageItem();
			
			if(is_numeric($parentId)) {
				$parentPage = $pageModel->find($this->_getParam('parent_id'));
				$form->setLocale($parentPage->locale);
			} else {
				$form->setLocale($parentId);
			}
						
			
			// $form->setLocale($this->_getParam('id'));
		}
			
		
		$naviModel = new Core_Model_Navigation();
		$navi = $naviModel->getNavigation();
		
		if(!$form->isValid($this->_getAllParams())) {
			
			$msg = new Emerald_Json_Message(Emerald_Json_Message::ERROR, 'Epic fail');
			$msg->errors = $form->getMessages(); 
			
		} else {
			$page->setFromArray($form->getValues());
			$pageModel->save($page, $form->getSubForm('page-permissions')->getValues());
			
			$msg = new Emerald_Json_Message(Emerald_Json_Message::SUCCESS, 'Ok');
			$msg->page_id = $page->id;
		}
		
		
		$this->view->message = $msg;
				
		
	}
	
	
	
	
	
	
}