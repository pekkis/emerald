<?php
class Admin_PageController extends Emerald_Controller_AdminAction
{
	

	
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
		
		$permForm = $form->getSubForm('page-permissions');
		
		$permForm->setDefaults(array(Core_Model_Group::GROUP_ROOT => array_keys(Emerald_Permission::getAll())));
		
		
		// $form->setDefaults($page->toArray());
		
		$this->view->form = $form;
		
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

			Zend_Debug::dump($form->getMessages());
			
			die('not ok');
			
			
			
			
		} else {
															
			Zend_Debug::dump($page->toArray());
			
			Zend_Debug::dump($form->getValues());
			
			$page->setFromArray($form->getValues());
						
			$pageModel->save($page, $form->getSubForm('page-permissions')->getValues());
						
			die();
			
		}
		
		
		
		
	}
	
	
	
	
	
	
}